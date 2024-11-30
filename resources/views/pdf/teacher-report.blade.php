<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Teacher Report - {{ $teacher['name'] }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 10px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 5px;
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            display: table-cell;
            padding: 5px;
        }
        .statistics-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .statistics-box {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            width: 25%;
        }
        .statistics-label {
            font-size: 12px;
            color: #666;
        }
        .statistics-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f3f4f6;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .status-present {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-late {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-absent {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Teacher Report</h1>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $teacher['name'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $teacher['email'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $teacher['phone_number'] ?? 'Not Provided' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">UID:</div>
                <div class="info-value">{{ $teacher['uid'] ?? 'Not Assigned' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">PIN:</div>
                <div class="info-value">{{ $teacher['pin'] ?? 'Not Assigned' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Class:</div>
                <div class="info-value">{{ $teacher['class'] ?? 'Not Assigned' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Section:</div>
                <div class="info-value">{{ $teacher['section'] ?? 'Not Assigned' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Specialization:</div>
                <div class="info-value">{{ $teacher['subject_specialization'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Salary:</div>
                <div class="info-value">৳{{ $teacher['salary_amount'] ?? 'Not Set' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">{{ $teacher['address'] ?? 'Not Provided' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Attendance Statistics</div>
        <div class="statistics-grid">
            <div class="statistics-box">
                <div class="statistics-label">Total Days</div>
                <div class="statistics-value">{{ $statistics['total_days'] }}</div>
            </div>
            <div class="statistics-box">
                <div class="statistics-label">Present Days</div>
                <div class="statistics-value">{{ $statistics['present_days'] }}</div>
            </div>
            <div class="statistics-box">
                <div class="statistics-label">Late Days</div>
                <div class="statistics-value">{{ $statistics['late_days'] }}</div>
            </div>
            <div class="statistics-box">
                <div class="statistics-label">Absent Days</div>
                <div class="statistics-value">{{ $statistics['absent_days'] }}</div>
            </div>
        </div>
        <div style="text-align: center; margin: 20px 0;">
            <strong>Overall Attendance Rate: {{ $statistics['attendance_percentage'] }}%</strong>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Attendance History</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Duration</th>
                    <th>Punches</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance['date'] }}</td>
                        <td>{{ $attendance['check_in'] ?? '-' }}</td>
                        <td>{{ $attendance['check_out'] ?? '-' }}</td>
                        <td>{{ $attendance['duration'] ?? '-' }}</td>
                        <td>{{ $attendance['total_punches'] }}</td>
                        <td>
                            <span class="status-badge status-{{ $attendance['status'] }}">
                                {{ ucfirst($attendance['status']) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No attendance records found for the selected period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
