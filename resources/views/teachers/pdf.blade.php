<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teachers Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #2196F3;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary {
            margin: 20px 0;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .summary strong {
            color: #333;
        }
        .list-items {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .list-items li {
            display: inline;
            margin-right: 5px;
        }
        .list-items li:after {
            content: ",";
        }
        .list-items li:last-child:after {
            content: "";
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Teacher Management Report</h1>
        <p>Generated on: {{ date('F d, Y - h:i A') }}</p>
    </div>

    <div class="summary">
        <strong>Total Teachers:</strong> {{ $teachers->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subjects</th>
                <th>Grades</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->id }}</td>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->email }}</td>
                <td>{{ $teacher->phone1 }}</td>
                <td>
                    <ul class="list-items">
                        @foreach(json_decode($teacher->subjects) as $subject)
                        <li>{{ $subject }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul class="list-items">
                        @foreach(json_decode($teacher->grades) as $grade)
                        <li>{{ $grade }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $teacher->username }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Student Management System. All rights reserved.</p>
    </div>
</body>
</html>