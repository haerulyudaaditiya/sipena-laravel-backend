<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        .payslip-info {
            margin-bottom: 20px;
        }
        .payslip-info table {
            width: 100%;
        }
        .employee-details {
            margin-bottom: 20px;
        }
        .salary-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .salary-details th, .salary-details td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .salary-details th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .summary {
            margin-top: 20px;
        }
        .summary table {
            width: 50%;
            float: right;
            border-collapse: collapse;
        }
        .summary th, .summary td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .summary .total {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('assets/img/logo_only.png') }}" alt="Logo" width="100"/>
            <h1>SIPENA Corp</h1>
            <p>Jl. Jenderal Sudirman No. 1, Jakarta Pusat, Indonesia</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <h2>SLIP GAJI</h2>
        </div>

        <div class="payslip-info">
            <table>
                <tr>
                    <td><strong>Nama Karyawan:</strong> {{ $salary->employee->name }}</td>
                    <td style="text-align: right;"><strong>Periode Gaji:</strong> {{ \Carbon\Carbon::parse($salary->salary_date)->format('F Y') }}</td>
                </tr>
                <tr>
                    <td><strong>NIK / ID Karyawan:</strong> {{ $salary->employee->employee_id }}</td>
                    <td style="text-align: right;"><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y') }}</td>
                </tr>
                 <tr>
                    <td><strong>Jabatan:</strong> {{ $salary->employee->position }}</td>
                </tr>
            </table>
        </div>

        <div class="salary-details">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <th colspan="2">Penerimaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gaji Pokok</td>
                                    <td style="text-align: right;">Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Tunjangan</td>
                                    <td style="text-align: right;">Rp {{ number_format($salary->allowances, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Bonus</td>
                                    <td style="text-align: right;">Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="font-weight: bold;">
                                    <td>Total Penerimaan (A)</td>
                                    @php $totalPenerimaan = $salary->basic_salary + $salary->allowances + $salary->bonus; @endphp
                                    <td style="text-align: right;">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <th colspan="2">Potongan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Potongan Lain-lain</td>
                                    <td style="text-align: right;">Rp {{ number_format($salary->deductions, 0, ',', '.') }}</td>
                                </tr>
                                 <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr style="font-weight: bold;">
                                    <td>Total Potongan (B)</td>
                                    <td style="text-align: right;">Rp {{ number_format($salary->deductions, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="summary clearfix">
            <table>
                <tr class="total">
                    <td>Gaji Bersih (A - B)</td>
                    <td style="text-align: right;">Rp {{ number_format($salary->net_salary, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer clearfix" style="margin-top: 100px;">
            <div style="float: right; width: 200px; text-align: center;">
                <p>Disetujui oleh,</p>
                <br><br><br>
                <p style="border-top: 1px solid #333; padding-top: 5px;">(HRD / Manajer Keuangan)</p>
            </div>
             <div style="float: left; width: 200px; text-align: center;">
                <p>Diterima oleh,</p>
                <br><br><br>
                <p style="border-top: 1px solid #333; padding-top: 5px;">{{ $salary->employee->name }}</p>
            </div>
        </div>
    </div>
</body>
</html>
