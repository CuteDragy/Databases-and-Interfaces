<?php
require_once "config.php";

if (empty($_SESSION['user'])) {
    header("Location: LoginMenu.php");
    exit();
}

$currentUserId = $_SESSION['user'];

// Fetch all assessment rows for this student via internships join
$stmt = mysqli_prepare($conn,
    "SELECT a.*
     FROM   assessments a
     JOIN   internships i ON i.internship_id = a.internship_id
     WHERE  i.student_id = ?
);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'i', $currentUserId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$rawRows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rawRows[] = $row;
}
mysqli_free_result($result);
mysqli_stmt_close($stmt);

// Group rows by internship_id
$grouped = [];
foreach ($rawRows as $row) {
    $grouped[$row['internship_id']][] = $row;
}

// For each internship, sum all score fields then divide by number of rows
$scoreFields = [
    'undertaking_projects',
    'health_safety_requirements',
    'knowledge',
    'report',
    'language_clarity',
    'lifelong_activities',
    'project_management',
    'time_management',
    'total_score',
];

$assessments = [];
foreach ($grouped as $internshipId => $rows) {
    $count = count($rows);

    $sums = array_fill_keys($scoreFields, 0.0);
    foreach ($rows as $r) {
        foreach ($scoreFields as $field) {
            $sums[$field] += (float)($r[$field] ?? 0);
        }
    }

    $averaged = [];
    foreach ($scoreFields as $field) {
        $averaged[$field] = $sums[$field] / $count;
    }

    $commentParts = [];
    foreach ($rows as $r) {
        $c = trim($r['comments'] ?? '');
        if ($c !== '') $commentParts[] = $c;
    }

    $assessments[] = [
        'internship_id' => $internshipId,
        'comments'      => implode(' | ', $commentParts),
    ] + $averaged;
}

usort($assessments, fn($a, $b) => $b['internship_id'] <=> $a['internship_id']);

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

    <?php if (!$hasResults): ?>
    <div class="empty-state">
      <i class='bx bx-inbox'></i>
      <p>No internship results yet</p>
      <span>When your supervisor submits an assessment, your results will appear here.</span>
    </div>

    <?php else: ?>
    <ul class="result-list" id="resultList">
      <?php foreach ($assessments as $index => $a):

        $internshipId = (int)$a['internship_id'];
        $undertaking  = (float)$a['undertaking_projects'];
        $healthSafety = (float)$a['health_safety_requirements'];
        $knowledge    = (float)$a['knowledge'];
        $report       = (float)$a['report'];
        $langClarity  = (float)$a['language_clarity'];
        $lifelong     = (float)$a['lifelong_activities'];
        $projMgmt     = (float)$a['project_management'];
        $timeMgmt     = (float)$a['time_management'];
        $totalScore   = (float)$a['total_score'];
        $comments     = htmlspecialchars($a['comments'] ?? '');

        $grade = match(true) {
            $totalScore >= 70 => ['label' => 'Distinction', 'class' => 'badge-accepted'],
            $totalScore >= 60 => ['label' => 'Credit',      'class' => 'badge-interview'],
            $totalScore >= 40 => ['label' => 'Pass',        'class' => 'badge-pending'],
            default           => ['label' => 'Fail',        'class' => 'badge-rejected'],
        };

        $scoreData = json_encode([
            'id'         => $internshipId,
            'grade'      => $grade,
            'totalScore' => $totalScore,
            'comments'   => $comments,
            'scores'     => [
                ['label' => 'Undertaking Tasks / Projects',             'value' => $undertaking,  'max' => 10],
                ['label' => 'Health &amp; Safety Requirements',         'value' => $healthSafety, 'max' => 10],
                ['label' => 'Connectivity &amp; Theoretical Knowledge', 'value' => $knowledge,    'max' => 10],
                ['label' => 'Report Presentation',                      'value' => $report,       'max' => 15],
                ['label' => 'Clarity of Language &amp; Illustration',   'value' => $langClarity,  'max' => 10],
                ['label' => 'Lifelong Learning Activities',             'value' => $lifelong,     'max' => 15],
                ['label' => 'Project Management',                       'value' => $projMgmt,     'max' => 15],
                ['label' => 'Time Management',                          'value' => $timeMgmt,     'max' => 15],
            ]
        ]);
      ?>

      <li class="result-item"
          data-id="<?= $internshipId ?>"
          data-index="<?= $index ?>"
          data-score='<?= htmlspecialchars($scoreData, ENT_QUOTES, 'UTF-8') ?>'>
        <i class='bx bx-star item-star' title="Star"></i>
        <span class="item-company">Internship #<?= $internshipId ?></span>
        <div class="item-body">
          <span class="item-subject">Internship Assessment</span>
          <span class="item-snippet"> — Total Score: <?= number_format($totalScore, 1) ?>%</span>
        </div>
        <span class="item-status-badge <?= $grade['class'] ?>"><?= $grade['label'] ?></span>
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
      <div class="drawer-body" id="drawerBody"></div>
    </div>

  </div>

  <div class="detail-overlay" id="detailOverlay"></div>

  <script>
    const resultList = document.getElementById('resultList');

    if (resultList) {
      const items = Array.from(document.querySelectorAll('.result-item'));
      let starredIds = JSON.parse(localStorage.getItem('starredAssessments') || '[]');

      function sortItems() {
        items.sort((a, b) => {
          const aS = a.querySelector('.item-star').classList.contains('starred');
          const bS = b.querySelector('.item-star').classList.contains('starred');
          if (aS && !bS) return -1;
          if (!aS && bS) return 1;
          return parseInt(b.dataset.id) - parseInt(a.dataset.id);
        });
        items.forEach(item => resultList.appendChild(item));
      }

      items.forEach(item => {
        const star = item.querySelector('.item-star');
        if (starredIds.includes(item.dataset.id)) {
          star.classList.add('starred', 'bxs-star');
          star.classList.remove('bx-star');
        }
      });
      sortItems();

      document.querySelectorAll('.item-star').forEach(star => {
        star.addEventListener('click', e => {
          e.stopPropagation();
          const item      = star.closest('.result-item');
          const id        = item.dataset.id;
          const isStarred = star.classList.toggle('starred');
          star.classList.toggle('bx-star',  !isStarred);
          star.classList.toggle('bxs-star',  isStarred);
          if (isStarred) { if (!starredIds.includes(id)) starredIds.push(id); }
          else           { starredIds = starredIds.filter(s => s !== id); }
          localStorage.setItem('starredAssessments', JSON.stringify(starredIds));
          sortItems();
        });
      });
    }

    const drawer         = document.getElementById('detailDrawer');
    const overlay        = document.getElementById('detailOverlay');
    const drawerMeta     = document.getElementById('drawerMeta');
    const drawerBody     = document.getElementById('drawerBody');
    const drawerCloseBtn = document.getElementById('drawerCloseBtn');

    function openDrawer(data) {
      drawerMeta.innerHTML =
        `<span class="item-status-badge ${data.grade.class}">${data.grade.label}</span>`;

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

      const commentsHtml = data.comments
        ? `<div class="comments-block">
             <div class="comments-label"><i class='bx bx-comment-detail'></i> Supervisor Comments</div>
             <div class="comments-body">${data.comments.replace(/\n/g, '<br>').replace(' | ', '<hr class="comment-divider">')}</div>
           </div>`
        : '';

      drawerBody.innerHTML = `
        <div class="score-grid">${cardsHtml}</div>
        <div class="total-score-row">
          <span class="total-score-label">Total Score</span>
          <span class="total-score-value ${data.grade.class}">${data.totalScore.toFixed(1)}%</span>
        </div>
        ${commentsHtml}`;

      drawer.classList.add('open');
      setTimeout(() => {
        drawerBody.querySelectorAll('.score-bar').forEach(bar => {
          bar.style.transition = 'width 0.6s ease';
          bar.style.width      = bar.dataset.pct;
        });
      }, 320);
    }

    function closeDrawer() { drawer.classList.remove('open'); }

    drawerCloseBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

    document.querySelectorAll('.result-item').forEach(item => {
      item.addEventListener('click', e => {
        if (e.target.matches('i.item-star, .item-star')) return;
        item.classList.add('active-row');
        document.querySelectorAll('.result-item').forEach(r => { if (r !== item) r.classList.remove('active-row'); });
        openDrawer(JSON.parse(item.dataset.score));
      });
    });
  </script>
</body>
</html>