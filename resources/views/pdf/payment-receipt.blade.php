<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .receipt-details {
            margin-bottom: 30px;
        }
        .student-details {
            margin-bottom: 30px;
        }
        .payment-details {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $school['logo'] }}" alt="School Logo" class="logo">
            <h1>{{ $school['name'] }}</h1>
            <p>{{ $school['address'] }}</p>
            <p>Phone: {{ $school['phone'] }}</p>
            <h2>Payment Receipt</h2>
        </div>

        <div class="receipt-details">
            <p><strong>Receipt No:</strong> {{ sprintf('REC-%06d', $payment->id) }}</p>
            <p><strong>Date:</strong> {{ $payment->created_at->format('F d, Y') }}</p>
        </div>

        <div class="student-details">
            <h3>Student Information</h3>
            <p><strong>Name:</strong> {{ $payment->student->name_en }}</p>
            <p><strong>ID:</strong> {{ $payment->student->student_id }}</p>
            <p><strong>Class:</strong> {{ $payment->student->schoolClass->name }} - {{ $payment->student->section->name }}</p>
        </div>

        <div class="payment-details">
            <h3>Payment Details</h3>
            <table>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>Monthly Fee for {{ date('F', mktime(0, 0, 0, $payment->month, 1)) }} {{ $payment->year }}</td>
                    <td>৳{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </table>

            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
        </div>

        <div class="footer">
            <p>This is a computer generated receipt.</p>
        </div>
    </div>
</body>
</html>