<?php
session_start();
require_once "config.php";

if (empty($_SESSION['user'])) {
    header("Location: LoginMenu.php");
    exit();
}

$currentUserId = $_SESSION['user'];

$assessments = [];
$stmt = mysqli_prepare($conn,
    "SELECT
        i.internship_id,
        i.startDate,
        i.endDate,
        c.company_name,
        AVG(a.undertaking_projects)       AS undertaking_projects,
        AVG(a.health_safety_requirements) AS health_safety_requirements,
        AVG(a.knowledge)                  AS knowledge,
        AVG(a.report)                     AS report,
        AVG(a.language_clarity)           AS language_clarity,
        AVG(a.lifelong_activities)        AS lifelong_activities,
        AVG(a.project_management)         AS project_management,
        AVG(a.time_management)            AS time_management,
        AVG(a.total_score)                AS total_score,
        GROUP_CONCAT(a.comments SEPARATOR ' | ') AS comments,
        COUNT(a.assessment_id)            AS assessment_count
     FROM internships i
     JOIN assessments a ON a.internship_id = i.internship_id
     JOIN companies c   ON c.company_id    = i.company_id
     WHERE i.student_id = ?
     GROUP BY i.internship_id
     ORDER BY i.internship_id DESC"
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
  <link rel="stylesheet" href="css/Result.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
  <div class="content-header">
    <h1>Internship Result</h1>
    <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50" style="margin-right:15px;"></a></div>
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

        $undertaking  = round((float)($a['undertaking_projects']       ?? 0), 1);
        $healthSafety = round((float)($a['health_safety_requirements'] ?? 0), 1);
        $knowledge    = round((float)($a['knowledge']                  ?? 0), 1);
        $report       = round((float)($a['report']                     ?? 0), 1);
        $langClarity  = round((float)($a['language_clarity']           ?? 0), 1);
        $lifelong     = round((float)($a['lifelong_activities']        ?? 0), 1);
        $projMgmt     = round((float)($a['project_management']         ?? 0), 1);
        $timeMgmt     = round((float)($a['time_management']            ?? 0), 1);
        $totalScore   = round((float)($a['total_score']                ?? 0), 1);
        $comments     = htmlspecialchars($a['comments']                ?? '');
        $companyName  = htmlspecialchars($a['company_name']            ?? 'Unknown Company');
        $assessCount  = (int)($a['assessment_count']                   ?? 0);

        $dateLabel = '';
        if (!empty($a['startDate']) && !empty($a['endDate'])) {
            $startFmt  = date('M Y', strtotime($a['startDate']));
            $endFmt    = date('M Y', strtotime($a['endDate']));
            $dateLabel = "$startFmt – $endFmt";
        }

        $grade = match(true) {
            $totalScore >= 70 => ['label' => 'Distinction', 'class' => 'badge-accepted'],
            $totalScore >= 60 => ['label' => 'Credit',      'class' => 'badge-interview'],
            $totalScore >= 40 => ['label' => 'Pass',        'class' => 'badge-pending'],
            default           => ['label' => 'Fail',        'class' => 'badge-rejected'],
        };

        $itemId    = (int)($a['internship_id'] ?? $index);
        $scoreData = json_encode([
            'id'              => $itemId,
            'company'         => $companyName,
            'date'            => $dateLabel,
            'grade'           => $grade,
            'totalScore'      => $totalScore,
            'comments'        => $comments,
            'assessmentCount' => $assessCount,
            'scores'          => [
                ['label' => 'Undertaking Tasks / Projects',               'value' => $undertaking,  'max' => 10],
                ['label' => 'Health & Safety Requirements',               'value' => $healthSafety, 'max' => 10],
                ['label' => 'Connectivity & Theoretical Knowledge',       'value' => $knowledge,    'max' => 10],
                ['label' => 'Report Presentation',                        'value' => $report,       'max' => 15],
                ['label' => 'Clarity of Language & Illustration',         'value' => $langClarity,  'max' => 10],
                ['label' => 'Lifelong Learning Activities',               'value' => $lifelong,     'max' => 15],
                ['label' => 'Project Management',                         'value' => $projMgmt,     'max' => 15],
                ['label' => 'Time Management',                            'value' => $timeMgmt,     'max' => 15],
            ]
        ]);
      ?>

      <li class="result-item"
          data-id="<?= $itemId ?>"
          data-index="<?= $index ?>"
          data-score='<?= htmlspecialchars($scoreData, ENT_QUOTES, 'UTF-8') ?>'>
        <i class='bx bx-star item-star' title="Star"></i>
        <span class="item-company"><?= $companyName ?></span>
        <div class="item-body">
          <span class="item-subject">Internship #<?= $itemId ?></span>
          <span class="item-snippet"> — Combined Score: <?= number_format($totalScore, 1) ?> / 100</span>
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
          <h2 class="drawer-title" id="drawerTitle">Internship Assessment</h2>
          <div class="drawer-meta" id="drawerMeta"></div>
        </div>
      </div>
      <div class="drawer-body" id="drawerBody"></div>
    </div>

  </div>

  <div id="detailOverlay"></div>

  <script>
  const resultList = document.getElementById('resultList');

  // ── Star / favourite logic ───────────────────────────────────────────────
  if (resultList) {
    const items = Array.from(document.querySelectorAll('.result-item'));
    let starredIds = JSON.parse(localStorage.getItem('starredAssessments') || '[]');

    function sortItems() {
      items.sort((a, b) => {
        const aStarred = a.querySelector('.item-star').classList.contains('starred');
        const bStarred = b.querySelector('.item-star').classList.contains('starred');
        if (aStarred && !bStarred) return -1;
        if (!aStarred && bStarred) return  1;
        return parseInt(b.dataset.id) - parseInt(a.dataset.id);
      });
      items.forEach(item => resultList.appendChild(item));
    }

    items.forEach(item => {
      const id   = item.dataset.id;
      const star = item.querySelector('.item-star');
      if (starredIds.includes(id)) {
        star.classList.add('starred', 'bxs-star');
        star.classList.remove('bx-star');
      }
    });
    sortItems();

    document.querySelectorAll('.item-star').forEach(star => {
      star.addEventListener('click', e => {
        e.stopPropagation(); // Prevent opening the drawer
        const item = star.closest('.result-item');
        const id   = item.dataset.id;
        const isStarred = star.classList.toggle('starred');
        star.classList.toggle('bx-star',   !isStarred);
        star.classList.toggle('bxs-star',   isStarred);
        
        if (isStarred) {
          if (!starredIds.includes(id)) starredIds.push(id);
        } else {
          starredIds = starredIds.filter(s => s !== id);
        }
        localStorage.setItem('starredAssessments', JSON.stringify(starredIds));
        sortItems();
      });
    });
  }

  // ── Drawer logic ────────────────────────────────────────────────────────
  const drawer         = document.getElementById('detailDrawer');
  const drawerTitle    = document.getElementById('drawerTitle');
  const drawerMeta     = document.getElementById('drawerMeta');
  const drawerBody     = document.getElementById('drawerBody');
  const drawerCloseBtn = document.getElementById('drawerCloseBtn');

  function openDrawer(data) {
    drawerTitle.textContent = data.company || 'Internship Assessment';

    const badgeHtml = `<span class="item-status-badge ${data.grade.class}">${data.grade.label}</span>`;
    const dateHtml  = data.date
      ? `<span class="detail-date"><i class='bx bx-calendar'></i> ${data.date}</span>`
      : '';
    const countHtml = `<span class="detail-count"><i class='bx bx-user-check'></i> Based on ${data.assessmentCount} assessment(s)</span>`;
    drawerMeta.innerHTML = badgeHtml + dateHtml + countHtml;

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
        <div class="comments-label"><i class='bx bx-comment-detail'></i>Comments</div>
        <div class="comments-body">${data.comments.replace(/\n/g, '<br>')}</div>
      </div>` : '';

    drawerBody.innerHTML = `
      <div class="score-grid">${cardsHtml}</div>
      <div class="total-score-row">
        <span class="total-score-label">Combined Score</span>
        <span class="total-score-value ${data.grade.class}">${data.totalScore.toFixed(1)} / 100</span>
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
    
    // Remove the active grey background from all items when closing
    document.querySelectorAll('.result-item').forEach(r => {
      r.classList.remove('active-row');
    });
  }

  // Close buttons and keys
  drawerCloseBtn.addEventListener('click', closeDrawer);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

  // Close drawer when clicking completely outside the drawer and list items
  document.addEventListener('click', e => {
    if (
      drawer.classList.contains('open') && 
      !drawer.contains(e.target) && 
      !e.target.closest('.result-item')
    ) {
      closeDrawer();
    }
  });

  // Result Item click events
  document.querySelectorAll('.result-item').forEach(item => {
    item.addEventListener('click', e => {
      // Ignore clicks if the user is clicking the star icon
      if (e.target.matches('i.item-star, .item-star')) return;
      
      // If the user clicks the row that is ALREADY open, close it (Toggle effect)
      if (item.classList.contains('active-row')) {
          closeDrawer();
          return;
      }

      // Add the active grey background to the newly clicked item
      item.classList.add('active-row');
      
      // Remove the active grey background from all OTHER items
      document.querySelectorAll('.result-item').forEach(r => {
        if (r !== item) r.classList.remove('active-row');
      });
      
      // Feed the new data into the drawer
      const data = JSON.parse(item.dataset.score);
      openDrawer(data);
    });
  });
</script>
  </script>
</body>
</html>