<div class="container">
    <div class="stat_raw clearfix">
        <div class="stat_box">
            <span><?= $text_transactions_count ?></span>
            <i class="fa fa-credit-card"></i>
            <span><?= $transactionsCount ?></span>
            <footer><?= $text_transactions_rate ?><a href="/transactions"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
        <div class="stat_box">
            <span><?= $text_under_review_count ?></span>
            <i class="fa fa-star-half-empty"></i>
            <span><?= $transactionsReviewCount ?></span>
            <footer><?= $text_under_review_rate ?><a href="/audit/review"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
        <div class="stat_box">
            <span><?= $text_done_review_count ?></span>
            <i class="fa fa-thumbs-o-up"></i>
            <span><?= $transactionsReviewedCount ?></span>
            <footer><?= $text_done_review_rate ?><a href="/audit/reviewed"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
        <div class="stat_box">
            <span><?= $text_clients_count ?></span>
            <i class="material-icons">contacts</i>
            <span><?= $clientsCount ?></span>
            <footer><?= $text_clients_rate ?><a href="/clients"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
        <div class="stat_box">
            <span><?= $text_cheque_count ?></span>
            <i class="fa fa-money"></i>
            <span><?= $issuesCheques ?></span>
            <footer><?= $text_cheque_rate ?><a href="/cheques/printed"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
        <div class="stat_box">
            <span><?= $text_cheque_received_count ?></span>
            <i class="fa fa-money"></i>
            <span><?= $handedOverCheques ?></span>
            <footer><?= $text_cheque_received_rate ?><a href="/cheques/printed"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
        <div class="stat_box">
            <span><?= $text_cheque_no_coverage_count ?></span>
            <i class="fa fa-credit-card-alt"></i>
            <span><?= $closedTransactions ?></span>
            <footer><?= $text_cheque_no_coverage_rate ?><a href="/transactions/closed"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
        <div class="stat_box">
            <span><?= $text_online_count ?></span>
            <i class="fa fa-users"></i>
            <span><?= $usersCount ?></span>
            <footer><?= $text_online_rate ?><a href="/users"><i class="material-icons"><?= $this->session->lang == 'ar' ? 'keyboard_arrow_left' : 'keyboard_arrow_right' ?></i></a></footer>
        </div>
    </div>
    <div class="stat_raw clearfix">
        <div class="chart">
            <header><?= $text_header_stat_1 ?>
                <a href="javascript:;"><i class="fa fa-ellipsis-v"></i></a>
                <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                <a href="javascript:;"><i class="material-icons">keyboard_arrow_up</i></a>
            </header>
            <canvas id="chart"></canvas>
        </div>
        <div class="chart">
            <header><?= $text_header_stat_2 ?>
                <a href="javascript:;"><i class="fa fa-ellipsis-v"></i></a>
                <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                <a href="javascript:;"><i class="material-icons">keyboard_arrow_up</i></a>
            </header>
            <canvas id="chart2"></canvas>
        </div>
    </div>
    <div class="stat_raw clearfix">
        <div class="chart">
            <header><?= $text_header_stat_3 ?>
                <a href="javascript:;"><i class="fa fa-ellipsis-v"></i></a>
                <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                <a href="javascript:;"><i class="material-icons">keyboard_arrow_up</i></a>
            </header>
            <div class="timeline">
                <?php if ($realTimeActions !== false): foreach ($realTimeActions as $realTimeAction): ?>
                <div class="timeline_item clearfix">
                    <img src="<?= $realTimeAction->Image === null ? '/img/user.png' : '/uploads/images/' . $realTimeAction->Image ?>" width="30">
                    <span><?= $realTimeAction->name ?> <?= (new DateTime($realTimeAction->Created))->format('Y-m-d H:i:s') ?></span>
                    <span><i class="fa fa-briefcase"></i><?= $realTimeAction->TransactionTitle ?> - <?= $this->language->get('text_index_status_' . $realTimeAction->StatusType) ?></span>
                </div>
                <?php endforeach; endif; ?>
                <?php if ($realTimeActions === false): ?>
                <div class="timeline_item clearfix">
                    <span><?= $text_no_actions_now ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="chart">
            <header><?= $text_header_stat_4 ?>
                <a href="javascript:;"><i class="fa fa-ellipsis-v"></i></a>
                <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                <a href="javascript:;"><i class="material-icons">keyboard_arrow_up</i></a>
            </header>
            <div class="timeline">
                <div class="timeline_item clearfix">
                    <span><?= $text_no_todos_now ?></span>
<!--                    <i class="fa fa-calendar"></i>-->
<!--                    <span>--><?//= date('Y-m-d H:i:s') ?><!--</span>-->
<!--                    <span>--><?//= $text_todo_1 ?><!--</span>-->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $barchart ?>