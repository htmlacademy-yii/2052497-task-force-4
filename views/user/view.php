<?php

use taskforce\business\Task;
use yii\helpers\Html;
?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?=Html::encode($user->name); ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="/img/man-glasses.png" width="191" height="190" alt="Фото пользователя">
                <div class="card-rate">
                    <div class="stars-rating big"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                    <span class="current-rate"><?=Html::encode($user->rating); ?></span>
                </div>
            </div>
            <p class="user-description">
              <?=Html::encode($user->description); ?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($user->categories as $category): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?=Html::encode($category->name); ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info">
                    <span class="country-info">Россия</span>, 
                    <span class="town-info"><?=Html::encode($user->city->name); ?></span>,
                    <span class="age-info"><?=$user->age ?></span> лет</p>
            </div>
        </div>
        <?php if (count($user->executorReviews) > 0): ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($user->executorReviews as $review): ?>
        <div class="response-card">
            <img class="customer-photo" src=<?=Html::encode($review->customer->avatar); ?> width="120" height="127" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <p class="feedback">«<?=Html::encode($review->message); ?>»</p>
                <p class="task">Задание «<a href="#" class="link link--small"><?=Html::encode($review->task->title); ?></a>» <?=Task::getAllStatuses()[$review->task->status]; ?></p>
            </div>
            <div class="feedback-wrapper">
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime(HTML::encode($review->dt_add)) ?></span></p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                    <dt>Всего заказов</dt>
                    <dd><?=$user->countDoneTasks; ?> выполнено, <?=$user->countFailTasks; ?> провалено</dd>
                    <dt>Место в рейтинге</dt>
                    <dd><?=$user->positionInRating; ?> место</dd>
                    <dt>Дата регистрации</dt>
                    <dd><?= Yii::$app->formatter->asDateTime(HTML::encode($user->dt_add), 'd MMMM Y') ?></dd>
                    <dt>Статус</dt>
                    <?php if ($user->status): ?>
                    <dd>Открыт для новых заказов</dd>
                    <?php else: ?>
                    <dd>Занят</dd> 
                    <?php endif; ?>  
            </dl>
        </div>
        <?php if ($user->show_contacts): ?>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <?php if ($user->phonenumber): ?>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--phone"><?= Html::encode($user->phonenumber); ?></a>
                </li>
                <?php endif; ?>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--email"><?= Html::encode($user->email); ?></a>
                </li>
                <?php if ($user->telegram): ?>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--tg"><?= Html::encode($user->telegram); ?></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</main>