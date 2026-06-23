<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Soil Test History Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #228B22;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #228B22;
            font-size: 24px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .report-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .report-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-info td {
            padding: 3px 10px;
            vertical-align: top;
        }
        .report-info td:first-child {
            font-weight: bold;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #228B22;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 6px 8px;
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
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $siteName }}</h1>
        <p>Soil Test History Report</p>
        <p>Generated on: {{ $generatedAt }}</p>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td>Total Tests:</td>
                <td>{{ $totalTests }}</td>
            </tr>
            @if($filters['date_from'])
            <tr>
                <td>Date From:</td>
                <td>{{ $filters['date_from'] }}</td>
            </tr>
            @endif
            @if($filters['date_to'])
            <tr>
                <td>Date To:</td>
                <td>{{ $filters['date_to'] }}</td>
            </tr>
            @endif
            @if($filters['soil_type'])
            <tr>
                <td>Soil Type:</td>
                <td>{{ $filters['soil_type'] }}</td>
            </tr>
            @endif
            @if($filters['location'])
            <tr>
                <td>Location Filter:</td>
                <td>{{ $filters['location'] }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($tests->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>N (%)</th>
                <th>P (ppm)</th>
                <th>K (ppm)</th>
                <th>pH</th>
                <th>Soil Type</th>
                <th>Recommendation</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tests as $test)
            <tr>
                <td>{{ $test->created_at ? $test->created_at->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $test->n }}</td>
                <td>{{ $test->p }}</td>
                <td>{{ $test->k }}</td>
                <td>{{ $test->ph }}</td>
                <td>{{ $test->soil_type ?: '-' }}</td>
                <td style="max-width: 200px; word-wrap: break-word;">{{ $test->recommendation ?: '-' }}</td>
                <td style="max-width: 150px; word-wrap: break-word;">{{ $test->address ?: ($test->latitude && $test->longitude ? $test->latitude . ', ' . $test->longitude : '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        No test results found for the selected criteria.
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by {{ $siteName }} on {{ $generatedAt }}</p>
        <p>For questions or support, please contact the system administrator.</p>
    </div>
</body>
</html> 