<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminWebController extends Controller
{
    /**
     * Show Public App Landing Page
     */
    public function landingPage()
    {
        $activeQuizzes = Quiz::where('status', 'active')->withCount('questions')->take(6)->get();
        $campaigns = Campaign::where('status', 'active')->latest()->get();
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_quizzes' => Quiz::count(),
            'total_attempts' => QuizAttempt::count(),
        ];
        return response()
            ->view('landing', compact('activeQuizzes', 'campaigns', 'stats'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1900 00:00:00 GMT');
    }

    /**
     * Show Admin Login Page
     */
    public function showLogin()
    {
        if (Auth::check() && strtolower(Auth::user()->role) === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Handle Admin Login Authentication
     */
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Always keep admin session persistent for web admin portal
        if (Auth::attempt(['email' => strtolower(trim($credentials['email'])), 'password' => $credentials['password']], true)) {
            $user = Auth::user();
            if (strtolower($user->role) === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Access denied. You do not have administrator permissions.'])->withInput();
        }

        return back()->withErrors(['email' => 'Invalid email address or password.'])->withInput();
    }

    /**
     * Process Admin Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Dashboard Overview Page
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_faculty' => User::where('role', 'faculty')->count(),
            'total_quizzes' => Quiz::count(),
            'active_quizzes' => Quiz::where('status', 'active')->count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_attempts' => QuizAttempt::count(),
            'avg_score' => round(QuizAttempt::avg('score') ?? 0, 1),
        ];

        $recentAttempts = QuizAttempt::with(['user', 'quiz'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttempts'));
    }

    /**
     * Quizzes Management Page
     */
    public function quizzes()
    {
        $quizzes = Quiz::withCount('questions')->latest()->get();
        return view('admin.quizzes', compact('quizzes'));
    }

    /**
     * Academic Management Page (Universities, Colleges, Departments, Courses, Branches, Subjects, Sections, Subsections)
     */
    public function academic()
    {
        return view('admin.academic');
    }

    /**
     * Quiz Attempts & Re-attempt Reset Management Page
     */
    public function attemptsPage()
    {
        return view('admin.attempts');
    }

    /**
     * Campaigns & Notices Page
     */
    public function campaigns()
    {
        $campaigns = Campaign::latest()->get();
        return view('admin.campaigns', compact('campaigns'));
    }

    /**
     * Users Directory Page
     */
    public function users(Request $request)
    {
        $query = User::query()->orderBy('created_at', 'desc');
        if ($request->filled('role') && in_array($request->role, ['student', 'faculty', 'admin'])) {
            $query->where('role', $request->role);
        }
        $users = $query->get()->map(function ($u) {
            $u->attempts_count = QuizAttempt::where('user_id', $u->id)->count();
            return $u;
        });

        return view('admin.users', compact('users'));
    }

    /**
     * Export structured Excel (.xls / .csv) results for a specific quiz
     */
    public function exportQuizResults($id, Request $request)
    {
        $quiz = Quiz::with('questions')->find($id);
        if (!$quiz) {
            return redirect()->back()->with('error', 'Quiz not found.');
        }

        $attempts = QuizAttempt::with(['user.branch', 'user.section', 'user.course', 'user.departmentModel'])
            ->where('quiz_id', $quiz->id)
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalQuestions = $quiz->questions->count();
        if ($totalQuestions === 0) {
            $totalQuestions = $attempts->max('total_questions') ?? 1;
        }

        $passingPercentage = $quiz->passing_marks ?? 40;
        $totalAttemptsCount = $attempts->count();
        $scores = $attempts->pluck('score');
        $highestScore = $totalAttemptsCount > 0 ? $scores->max() : 0;
        $lowestScore = $totalAttemptsCount > 0 ? $scores->min() : 0;
        $avgScore = $totalAttemptsCount > 0 ? round($scores->avg(), 2) : 0;
        
        $passedCount = 0;
        foreach ($attempts as $att) {
            $pct = $totalQuestions > 0 ? ($att->score / $totalQuestions) * 100 : 0;
            if ($pct >= $passingPercentage) {
                $passedCount++;
            }
        }
        $passRate = $totalAttemptsCount > 0 ? round(($passedCount / $totalAttemptsCount) * 100, 1) : 0;

        $format = strtolower($request->query('format', 'excel'));

        $safeTitle = preg_replace('/[^A-Za-z0-9_\-]/', '_', $quiz->title);
        $dateStr = date('Y-m-d_H-i');
        $filename = "Quiz_Result_{$safeTitle}_{$dateStr}";

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function () use ($quiz, $attempts, $totalQuestions, $passingPercentage) {
                $file = fopen('php://output', 'w');
                // UTF-8 BOM for Excel CSV compatibility
                fputs($file, "\xEF\xBB\xBF");

                // Quiz Overview Header
                fputcsv($file, ['QUIZ RESULT REPORT']);
                fputcsv($file, ['Quiz Title', $quiz->title]);
                fputcsv($file, ['Subject', $quiz->subject ?? 'General']);
                fputcsv($file, ['Instructor', $quiz->instructor ?? 'N/A']);
                fputcsv($file, ['Duration', ($quiz->duration_minutes ?? 'N/A') . ' Minutes']);
                fputcsv($file, ['Total Questions', $totalQuestions]);
                fputcsv($file, ['Passing Marks (%)', $passingPercentage . '%']);
                fputcsv($file, ['Total Submissions', $attempts->count()]);
                fputcsv($file, []);

                // Column Headers
                $cols = [
                    'Rank',
                    'Roll Number',
                    'Student Name',
                    'Email',
                    'Department',
                    'Course',
                    'Branch',
                    'Section',
                    'Semester',
                    'Marks Obtained',
                    'Total Marks',
                    'Percentage (%)',
                    'Result Status',
                    'Submission Type',
                    'Violations Count',
                    'Auto Submit Reason',
                    'IP Address',
                    'Location',
                    'Submitted At',
                ];

                foreach ($quiz->questions as $idx => $q) {
                    $cols[] = 'Q' . ($idx + 1) . ' Score';
                }

                fputcsv($file, $cols);

                $rank = 1;
                foreach ($attempts as $att) {
                    $u = $att->user;
                    $pct = $totalQuestions > 0 ? round(($att->score / $totalQuestions) * 100, 2) : 0;
                    $status = $pct >= $passingPercentage ? 'PASSED' : 'FAILED';

                    $row = [
                        $rank++,
                        $u ? ($u->roll_number ?? 'N/A') : 'N/A',
                        $u ? $u->name : 'Unknown Student',
                        $u ? $u->email : 'N/A',
                        $u ? ($u->departmentModel->name ?? $u->department ?? 'N/A') : 'N/A',
                        $u ? ($u->course->name ?? 'N/A') : 'N/A',
                        $u ? ($u->branch->code ?? $u->branch->name ?? 'N/A') : 'N/A',
                        $u ? ($u->section->name ?? 'N/A') : 'N/A',
                        $u ? ($u->semester ?? 'N/A') : 'N/A',
                        $att->score,
                        $totalQuestions,
                        $pct . '%',
                        $status,
                        strtoupper($att->submission_type ?? 'MANUAL'),
                        $att->violations_count ?? 0,
                        $att->auto_submit_reason ?? '-',
                        $att->ip_address ?? 'N/A',
                        $att->location ?? 'N/A',
                        $att->created_at ? $att->created_at->format('Y-m-d H:i:s') : 'N/A',
                    ];

                    $userAnswers = is_array($att->user_answers) ? $att->user_answers : [];
                    foreach ($quiz->questions as $q) {
                        $userAns = $userAnswers[$q->id] ?? null;
                        if ($userAns === null) {
                            $row[] = 'Unanswered';
                        } else {
                            if ($q->type === 'multiple') {
                                $correctAnsArr = is_array($q->correct_option) ? $q->correct_option : [$q->correct_option];
                                $userAnsArr = is_array($userAns) ? $userAns : [$userAns];
                                sort($correctAnsArr); sort($userAnsArr);
                                $row[] = ($correctAnsArr === $userAnsArr) ? 'Correct (1)' : 'Incorrect (0)';
                            } else {
                                $row[] = ((string)$userAns === (string)$q->correct_option) ? 'Correct (1)' : 'Incorrect (0)';
                            }
                        }
                    }

                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Default: Structured Excel (.xls) HTML Document Format
        $html = '<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Quiz Results</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #1E293B; }
    .header-table { margin-bottom: 20px; border-collapse: collapse; width: 100%; }
    .header-table td { padding: 8px 12px; border: 1px solid #CBD5E1; }
    .title-row { background-color: #0F172A; color: #FFFFFF; font-size: 16pt; font-weight: bold; text-align: center; }
    .subtitle-row { background-color: #1E293B; color: #E2E8F0; font-size: 11pt; text-align: center; }
    .card-label { background-color: #F1F5F9; font-weight: bold; color: #334155; }
    .card-val { font-weight: bold; color: #0F172A; }
    .data-table { border-collapse: collapse; width: 100%; font-size: 11px; }
    .data-table th { background-color: #1E293B; color: #FFFFFF; font-weight: bold; border: 1px solid #475569; padding: 10px 8px; text-align: center; white-space: nowrap; }
    .data-table td { border: 1px solid #CBD5E1; padding: 8px 6px; vertical-align: middle; text-align: left; }
    .rank-col { text-align: center; font-weight: bold; background-color: #F8FAFC; }
    .score-col { text-align: center; font-weight: bold; background-color: #ECFDF5; color: #047857; }
    .pass-tag { background-color: #D1FAE5; color: #065F46; font-weight: bold; text-align: center; }
    .fail-tag { background-color: #FEE2E2; color: #991B1B; font-weight: bold; text-align: center; }
    .auto-tag { background-color: #FEF3C7; color: #92400E; font-weight: bold; text-align: center; }
    .manual-tag { background-color: #E0F2FE; color: #0369A1; font-weight: bold; text-align: center; }
    .q-correct { background-color: #DCFCE7; color: #166534; text-align: center; font-weight: bold; }
    .q-incorrect { background-color: #FEE2E2; color: #991B1B; text-align: center; }
    .q-unanswered { background-color: #F1F5F9; color: #64748B; text-align: center; }
</style>
</head>
<body>

<table class="header-table">
    <tr>
        <td colspan="6" class="title-row">ACADOVA QUIZ ASSESSMENT RESULT REPORT</td>
    </tr>
    <tr>
        <td colspan="6" class="subtitle-row">Official Marksheet & Anti-Cheat Telemetry Data</td>
    </tr>
    <tr>
        <td class="card-label" width="16%">Quiz Title:</td>
        <td class="card-val" width="34%">' . htmlspecialchars($quiz->title) . '</td>
        <td class="card-label" width="16%">Total Submissions:</td>
        <td class="card-val" width="34%">' . $totalAttemptsCount . ' Student(s)</td>
    </tr>
    <tr>
        <td class="card-label">Subject / Category:</td>
        <td class="card-val">' . htmlspecialchars($quiz->subject ?? 'General') . '</td>
        <td class="card-label">Average Score:</td>
        <td class="card-val">' . $avgScore . ' / ' . $totalQuestions . '</td>
    </tr>
    <tr>
        <td class="card-label">Instructor:</td>
        <td class="card-val">' . htmlspecialchars($quiz->instructor ?? 'N/A') . '</td>
        <td class="card-label">Highest / Lowest:</td>
        <td class="card-val">' . $highestScore . ' Max | ' . $lowestScore . ' Min</td>
    </tr>
    <tr>
        <td class="card-label">Duration & Status:</td>
        <td class="card-val">' . ($quiz->duration_minutes ?? 0) . ' Mins (' . strtoupper($quiz->status ?? 'ACTIVE') . ')</td>
        <td class="card-label">Pass Rate:</td>
        <td class="card-val">' . $passRate . '% (' . $passedCount . ' Passed / ' . ($totalAttemptsCount - $passedCount) . ' Failed)</td>
    </tr>
</table>

<br>

<table class="data-table">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Roll Number</th>
            <th>Student Name</th>
            <th>Email Address</th>
            <th>Department</th>
            <th>Course</th>
            <th>Branch</th>
            <th>Section</th>
            <th>Semester</th>
            <th>Score</th>
            <th>Total Marks</th>
            <th>Percentage</th>
            <th>Status</th>
            <th>Submission Type</th>
            <th>Violations</th>
            <th>Auto-Submit Reason</th>
            <th>IP Address</th>
            <th>Location</th>
            <th>Submitted Date</th>';

        foreach ($quiz->questions as $idx => $q) {
            $html .= '<th>Q' . ($idx + 1) . ' Result</th>';
        }

        $html .= '</tr>
    </thead>
    <tbody>';

        if ($totalAttemptsCount === 0) {
            $colSpan = 19 + $quiz->questions->count();
            $html .= '<tr><td colspan="' . $colSpan . '" style="text-align: center; padding: 20px; color: #64748B;">No student attempts recorded for this quiz yet.</td></tr>';
        } else {
            $rank = 1;
            foreach ($attempts as $att) {
                $u = $att->user;
                $pct = $totalQuestions > 0 ? round(($att->score / $totalQuestions) * 100, 1) : 0;
                $isPass = $pct >= $passingPercentage;
                $statusClass = $isPass ? 'pass-tag' : 'fail-tag';
                $statusText = $isPass ? 'PASSED' : 'FAILED';

                $isAuto = strtolower($att->submission_type ?? '') === 'auto';
                $subClass = $isAuto ? 'auto-tag' : 'manual-tag';
                $subText = $isAuto ? 'AUTO' : 'MANUAL';

                $html .= '<tr>
                    <td class="rank-col">#' . $rank++ . '</td>
                    <td>' . htmlspecialchars($u ? ($u->roll_number ?? 'N/A') : 'N/A') . '</td>
                    <td><b>' . htmlspecialchars($u ? $u->name : 'Unknown Student') . '</b></td>
                    <td>' . htmlspecialchars($u ? $u->email : 'N/A') . '</td>
                    <td>' . htmlspecialchars($u ? ($u->departmentModel->name ?? $u->department ?? 'N/A') : 'N/A') . '</td>
                    <td>' . htmlspecialchars($u ? ($u->course->name ?? 'N/A') : 'N/A') . '</td>
                    <td>' . htmlspecialchars($u ? ($u->branch->code ?? $u->branch->name ?? 'N/A') : 'N/A') . '</td>
                    <td>' . htmlspecialchars($u ? ($u->section->name ?? 'N/A') : 'N/A') . '</td>
                    <td>' . htmlspecialchars($u ? ($u->semester ?? 'N/A') : 'N/A') . '</td>
                    <td class="score-col">' . $att->score . '</td>
                    <td style="text-align: center;">' . $totalQuestions . '</td>
                    <td style="text-align: center; font-weight: bold;">' . $pct . '%</td>
                    <td class="' . $statusClass . '">' . $statusText . '</td>
                    <td class="' . $subClass . '">' . $subText . '</td>
                    <td style="text-align: center; ' . ($att->violations_count > 0 ? 'color: #DC2626; font-weight: bold;' : '') . '">' . ($att->violations_count ?? 0) . '</td>
                    <td>' . htmlspecialchars($att->auto_submit_reason ?? '-') . '</td>
                    <td>' . htmlspecialchars($att->ip_address ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($att->location ?? 'N/A') . '</td>
                    <td>' . ($att->created_at ? $att->created_at->format('M d, Y H:i') : 'N/A') . '</td>';

                $userAnswers = is_array($att->user_answers) ? $att->user_answers : [];
                foreach ($quiz->questions as $q) {
                    $userAns = $userAnswers[$q->id] ?? null;
                    if ($userAns === null) {
                        $html .= '<td class="q-unanswered">-</td>';
                    } else {
                        if ($q->type === 'multiple') {
                            $correctAnsArr = is_array($q->correct_option) ? $q->correct_option : [$q->correct_option];
                            $userAnsArr = is_array($userAns) ? $userAns : [$userAns];
                            sort($correctAnsArr); sort($userAnsArr);
                            $isCorrect = ($correctAnsArr === $userAnsArr);
                        } else {
                            $isCorrect = ((string)$userAns === (string)$q->correct_option);
                        }

                        if ($isCorrect) {
                            $html .= '<td class="q-correct">✓ Correct (1)</td>';
                        } else {
                            $html .= '<td class="q-incorrect">✗ Incorrect (0)</td>';
                        }
                    }
                }

                $html .= '</tr>';
            }
        }

        $html .= '</tbody>
</table>

</body>
</html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xls\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}

