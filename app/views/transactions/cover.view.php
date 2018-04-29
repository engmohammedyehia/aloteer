<div class="container">
    <div class="cover clearfix">
        <img src="/img/coverlogo.jpg" width="200">
        <div class="transaction_date">
            <?= $text_date ?><?= $transaction->Created ?>
        </div>
        <h1><?= $title ?></h1>
        <p><?= $text_reviewed_by ?></p>
        <table>
            <tr>
                <td><span class="label"><?= $text_client ?></span><?= $client ?></td>
            </tr>
            <tr>
                <td><span class="label"><?= $text_amount ?></span><?= $cheque->Amount ?> <?= $text_riyal ?></td>
            </tr>
            <tr>
                <td><span class="label"><?= $text_amount_literal ?></span><?= $cheque->AmountLiteral ?> <?= $text_riyal ?> فقط لا غير</td>
            </tr>
            <tr>
                <td><span class="label"><?= $text_for ?></span><?= $cheque->Reason ?></td>
            </tr>
            <tr>
                <td><span class="label"><?= $text_bank ?></span><?= $bank ?><span class="label" style="margin-right: 200px;"><?= $text_bank_account ?></span><?= $bankAccount->BankAccountNumber ?></td>
            </tr>
            <tr><td></td></tr>
            <tr>
                <td>
                    <?php if(false !== $signatureRequest): foreach ($signatureRequest as $signatureRequestItem): ?>
                        <div class="signature">
                            <span class="label"><?= ${'text_'.$signatureRequestItem->GroupId} ?></span>
                            <img width="100" src="/uploads/images/<?= $signatureRequestItem->Signature ?>">
                        </div>
                    <?php endforeach; endif; ?>
                    <div class="signature">
                        <span class="label"><?= $text_final_confirm ?></span>
                    </div>
                </td>
            </tr>
        </table>
        <button class="print" onclick="window.print();"><?= $text_label_print ?></button>
    </div>
</div>
<style>
    @media print {
        button {
            display: none;
        }
    }
</style>