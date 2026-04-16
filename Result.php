<?php
require_once "config.php";

if (empty($_SESSION['user'])) {
    header("Location: LoginMenu.php");
    exit();
}

$currentUserId = $_SESSION['user'];

$assessments = [];
$stmt = mysqli_prepare($conn,
  "SELECT * FROM `assessments`
     WHERE `user_id` = ?
     ORDER BY `assessment_id` DESC"
);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'i', $currentUserId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $assessments[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_stmt_close($stmt);

$totalCount = count($assessments);
$hasResults = $totalCount > 0;
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your Internship Result</title>
  <link rel="stylesheet" href="Result.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
  <div class="content-header">
    <h1>Internship Result</h1>
  </div>

  <div class="resultContainer">

    <div class="toolbar">
      <div class="checkbox-wrapper">
        <input type="checkbox" class="select-all-checkbox" id="selectAll" title="Select all" />
        <span class="checkbox-dropdown" id="dropdownToggle" title="More select options">
          <i class='bx bx-chevron-down'></i>
        </span>
      </div>
      <span class="selected-count" id="selectedCount">0 selected</span>
      <div class="toolbar-actions" id="toolbarActions">
        <div class="toolbar-divider"></div>
        <button class="toolbar-btn" title="Archive"><i class='bx bx-archive-in'></i></button>
        <button class="toolbar-btn" title="Mark as read"><i class='bx bx-envelope-open'></i></button>
        <button class="toolbar-btn" title="More options"><i class='bx bx-dots-vertical-rounded'></i></button>
      </div>
    </div>

    <?php if (!$hasResults): ?>
    <div class="empty-state">
      <i class='bx bx-inbox'></i>
      <p>No internship results yet</p>
      <span>When your supervisor submits an assessment, your results will appear here.</span>
    </div>


    <?php else: ?>
    <ul class="result-list" id="resultList">
      <?php foreach ($assessments as $index => $a):

        $undertaking  = (float)($a['undertaking_projects']       ?? 0);
        $healthSafety = (float)($a['health_safety_requirements'] ?? 0);
        $knowledge    = (float)($a['knowledge']                  ?? 0);
        $report       = (float)($a['report']                     ?? 0);
        $langClarity  = (float)($a['language_clarity']           ?? 0);
        $lifelong     = (float)($a['lifelong_activities']        ?? 0);
        $projMgmt     = (float)($a['project_management']         ?? 0);
        $timeMgmt     = (float)($a['time_management']            ?? 0);
        $totalScore   = (float)($a['total_score']                ?? 0);
        $comments     = htmlspecialchars($a['comments']          ?? '');

        $rawDate   = $a['created_at'] ?? $a['date'] ?? null;
        $dateLabel = '';
        if ($rawDate) {
            $ts        = strtotime($rawDate);
            $dateLabel = (date('Y', $ts) === date('Y'))
                ? date('M j', $ts)
                : date('M j, Y', $ts);
        }

        $grade = match(true) {
            $totalScore >= 85 => ['label' => 'Distinction', 'class' => 'badge-accepted'],
            $totalScore >= 70 => ['label' => 'Credit',      'class' => 'badge-interview'],
            $totalScore >= 50 => ['label' => 'Pass',        'class' => 'badge-pending'],
            default           => ['label' => 'Fail',        'class' => 'badge-rejected'],
        };

        $readClass = empty($a['is_read']) ? 'unread' : 'read';
        $itemId = (int)($a['assessment_id'] ?? $index);
        $scoreData = json_encode([
            'id'          => $itemId,
            'date'        => $dateLabel,
            'grade'       => $grade,
            'totalScore'  => $totalScore,
            'comments'    => $comments,
            'scores'      => [
                ['label' => 'Undertaking Tasks / Projects',          'value' => $undertaking,  'max' => 10],
                ['label' => 'Health &amp; Safety Requirements',      'value' => $healthSafety, 'max' => 10],
                ['label' => 'Connectivity &amp; Theoretical Knowledge', 'value' => $knowledge, 'max' => 10],
                ['label' => 'Report Presentation',                   'value' => $report,       'max' => 15],
                ['label' => 'Clarity of Language &amp; Illustration','value' => $langClarity,  'max' => 10],
                ['label' => 'Lifelong Learning Activities',          'value' => $lifelong,     'max' => 15],
                ['label' => 'Project Management',                    'value' => $projMgmt,     'max' => 15],
                ['label' => 'Time Management',                       'value' => $timeMgmt,     'max' => 15],
            ]
        ]);
      ?>

      <li class="result-item <?= $readClass ?>"
          data-id="<?= $itemId ?>"
          data-index="<?= $index ?>"
          data-score='<?= htmlspecialchars($scoreData, ENT_QUOTES, 'UTF-8') ?>'>
        <input type="checkbox" class="item-checkbox" title="Select" />
        <i class='bx bx-star item-star' title="Star"></i>
        <span class="item-company">Assessment #<?= $itemId ?></span>
        <div class="item-body">
          <span class="item-subject">Internship Assessment</span>
          <span class="item-snippet"> — Total Score: <?= number_format($totalScore, 1) ?>%</span>
        </div>
        <span class="item-status-badge <?= $grade['class'] ?>"><?= $grade['label'] ?></span>
        <?php if ($dateLabel): ?>
        <span class="item-date"><?= $dateLabel ?></span>
        <?php endif; ?>
      </li>

      <?php endforeach; ?>
    </ul>

    <div class="result-footer">
      <span>1–<?= $totalCount ?> of <?= $totalCount ?></span>
      <button class="nav-btn" disabled title="Newer"><i class='bx bx-chevron-left'></i></button>
      <button class="nav-btn" disabled title="Older"><i class='bx bx-chevron-right'></i></button>
    </div>
    <?php endif; ?>

        <div class="detail-drawer" id="detailDrawer">
    <div class="drawer-header">
      <button class="drawer-close-btn" id="drawerCloseBtn" title="Close">
        <i class='bx bx-x'></i>
      </button>
      <div class="drawer-title-group">
        <h2 class="drawer-title">Internship Assessment</h2>
        <div class="drawer-meta" id="drawerMeta"></div>
      </div>
    </div>
    <div class="drawer-body" id="drawerBody">
    </div>
  </div>

  </div>

  <div class="detail-overlay" id="detailOverlay"></div>

  <script>
    const selectAll      = document.getElementById('selectAll');
    const toolbarActions = document.getElementById('toolbarActions');
    const selectedCount  = document.getElementById('selectedCount');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    function updateToolbar() {
      const checked = document.querySelectorAll('.item-checkbox:checked').length;
      if (checked > 0) {
        toolbarActions.classList.add('visible');
        selectedCount.classList.add('visible');
        selectedCount.textContent = `${checked} selected`;
        selectAll.indeterminate = checked < itemCheckboxes.length;
        selectAll.checked       = checked === itemCheckboxes.length;
      } else {
        toolbarActions.classList.remove('visible');
        selectedCount.classList.remove('visible');
        selectAll.indeterminate = false;
        selectAll.checked       = false;
      }
      document.querySelectorAll('.result-item').forEach(item => {
        const cb = item.querySelector('.item-checkbox');
        item.classList.toggle('selected', cb && cb.checked);
      });
    }

    selectAll.addEventListener('change', () => {
      itemCheckboxes.forEach(cb => cb.checked = selectAll.checked);
      updateToolbar();
    });
    itemCheckboxes.forEach(cb => cb.addEventListener('change', updateToolbar));

    document.querySelectorAll('.item-star').forEach(star => {
      star.addEventListener('click', e => {
        e.stopPropagation();
        star.classList.toggle('starred');
        star.classList.toggle('bx-star',  !star.classList.contains('starred'));
        star.classList.toggle('bxs-star',  star.classList.contains('starred'));
      });
    });

    const drawer        = document.getElementById('detailDrawer');
    const overlay       = document.getElementById('detailOverlay');
    const drawerMeta    = document.getElementById('drawerMeta');
    const drawerBody    = document.getElementById('drawerBody');
    const drawerCloseBtn = document.getElementById('drawerCloseBtn');

    function openDrawer(data) {
      const badgeHtml = `<span class="item-status-badge ${data.grade.class}">${data.grade.label}</span>`;
      const dateHtml  = data.date
        ? `<span class="detail-date"><i class='bx bx-calendar'></i> ${data.date}</span>`
        : '';
      drawerMeta.innerHTML = badgeHtml + dateHtml;

      const cardsHtml = data.scores.map(s => {
        const pct = Math.min(100, (s.value / s.max) * 100).toFixed(1);
        return `
          <div class="score-card">
            <div class="score-label">${s.label}</div>
            <div class="score-bar-wrap">
              <div class="score-bar" data-pct="${pct}%" style="width:0%"></div>
            </div>
            <div class="score-value">
              ${s.value.toFixed(1)} / ${s.max}
              <span class="score-weight">(${s.max}%)</span>
            </div>
          </div>`;
      }).join('');

      const commentsHtml = data.comments ? `
        <div class="comments-block">
          <div class="comments-label"><i class='bx bx-comment-detail'></i> Supervisor Comments</div>
          <div class="comments-body">${data.comments.replace(/\n/g, '<br>')}</div>
        </div>` : '';

      drawerBody.innerHTML = `
        <div class="score-grid">${cardsHtml}</div>
        <div class="total-score-row">
          <span class="total-score-label">Total Score</span>
          <span class="total-score-value ${data.grade.class}">${data.totalScore.toFixed(1)}%</span>
        </div>
        ${commentsHtml}
      `;

      drawer.classList.add('open');

      setTimeout(() => {
        drawerBody.querySelectorAll('.score-bar').forEach(bar => {
          bar.style.transition = 'width 0.6s ease';
          bar.style.width      = bar.dataset.pct;
        });
      }, 320);
    }

    function closeDrawer() {
      drawer.classList.remove('open');
    }

    drawerCloseBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

    document.querySelectorAll('.result-item').forEach(item => {
      item.addEventListener('click', e => {
        if (e.target.matches('input[type="checkbox"], .item-checkbox, i.item-star, .item-star')) return;

        item.classList.replace('unread', 'read');
        item.classList.add('active-row');
        document.querySelectorAll('.result-item').forEach(r => {
          if (r !== item) r.classList.remove('active-row');
        });

        const data = JSON.parse(item.dataset.score);
        openDrawer(data);
      });
    });
  </script>
</body>
</html>