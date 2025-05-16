<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shift Table</title>
    <style>
        table { border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 5px 10px; text-align: center; }
        th { background-color: yellow; }
        .header { background-color: yellow; font-weight: bold; }
    </style>
</head>
<body>

<form method="GET" action="/shift-table">
    <label>Bulan:</label>
    <input type="number" name="bulan" value="{{ $month ?? '' }}" min="1" max="12" required>

    <label>Tahun:</label>
    <input type="number" name="tahun" value="{{ $year ?? '' }}" min="1900" required>

    <button type="submit">Proses</button>
</form>

@if (!empty($days))
    <table>
        <tr>
            <th rowspan="2">Nama</th>
            <th colspan="{{ count($days) }}">Bulan</th>
        </tr>
        <tr>
            @foreach ($days as $day)
                <th>{{ $day }}</th>
            @endforeach
        </tr>
        @foreach ($names as $name)
            <tr>
                <td class="header">{{ $name }}</td>
                @foreach ($days as $day)
                    <td></td>
                @endforeach
            </tr>
        @endforeach
    </table>
@endif

</body>
</html>
