<div class="container">
    <h1 style="font-size: 24px;text-align: center; margin-top: 50px;margin-bottom: 30px;"><?= $transaction->TransactionTitle ?></h1>
    <p style="margin-bottom: 30px;"><?= $text_reviewed_by ?></p>
    <table class="data">
        <thead>
        <tr>
            <th style="font-family: 'NeoSansArabic' !important; font-weight: normal"><?= $text_employee_name ?></th>
            <th style="font-family: 'NeoSansArabic' !important; font-weight: normal"><?= $text_employee_signature ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(false !== $signatureRequest): foreach ($signatureRequest as $signatureRequestItem): ?>
            <tr>
                <td style="vertical-align: middle"><?= $signatureRequestItem->EmpName ?></td>
                <td><?= (int) $signatureRequestItem->Approved === 1 ? '<img src="/uploads/images/' . $signatureRequestItem->Signature . '">' : $text_no_sign_yet ?></td>
            </tr>
        <?php endforeach; endif; ?>
            <tr>
                <td></td>
                <td>
                    <style>
                        @media print {
                            button {
                                display: none;
                            }
                        }
                    </style>
                    <button onclick="window.print();"><?= $text_label_print ?></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>