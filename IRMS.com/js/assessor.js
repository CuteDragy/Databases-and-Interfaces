/**
 * assessor.js  v4
 * Uses a plain HTML form POST — no JSON, no fetch().
 * Scores submitted as scores[0]..scores[7], notes as notes[0]..notes[7]
 */

const SHARED_CRITERIA = [
    { component: "Undertaking Tasks/Projects",                       weight: 10 },
    { component: "Health and Safety Requirements at the Workplace",   weight: 10 },
    { component: "Connectivity and Use of Theoretical Knowledge",     weight: 10 },
    { component: "Presentation of the Report as a Written Document",  weight: 15 },
    { component: "Clarity of Language and Illustration",              weight: 10 },
    { component: "Lifelong Learning Activities",                      weight: 15 },
    { component: "Project Management",                                weight: 15 },
    { component: "Time Management",                                   weight: 15 },
];

const CRITERIA = {
    lecturer:   SHARED_CRITERIA,
    supervisor: SHARED_CRITERIA,
};

// ── Called by detectRole() in markentry.php ───────────────────────────────────
function buildAssessmentTable() {
    const role = document.getElementById("hiddenRole").value;
    renderAssessmentTable(role);
}

// ── Render table rows with plain POST-friendly inputs ────────────────────────
function renderAssessmentTable(role) {
    const tbody = document.getElementById("assessmentBody");
    tbody.innerHTML = "";

    const rows = CRITERIA[role];
    if (!rows) return;

    rows.forEach((item, index) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${item.component}</td>
            <td>${item.weight}%</td>
            <td>
                <select name="scores[${index}]" class="score-select" data-index="${index}" onchange="recalculate()" required>
                    <option value="">--</option>
                    <option value="1">1 – Poor</option>
                    <option value="2">2 – Below Average</option>
                    <option value="3">3 – Average</option>
                    <option value="4">4 – Good</option>
                    <option value="5">5 – Excellent</option>
                </select>
            </td>
            <td class="weighted-cell" id="weighted-${index}">0.00</td>
            <td>
                <textarea name="notes[${index}]" class="notes-input" rows="2"
                    placeholder="Optional remarks..."></textarea>
            </td>
        `;
        tbody.appendChild(tr);
    });

    recalculate();
}

// ── Recalculate weighted contributions and total ─────────────────────────────
function recalculate() {
    const role = document.getElementById("hiddenRole").value;
    const rows = CRITERIA[role] || [];
    let total = 0;

    document.querySelectorAll(".score-select").forEach((sel, index) => {
        const score    = parseFloat(sel.value) || 0;
        const weight   = rows[index]?.weight || 0;
        const weighted = (score / 5) * weight;
        total += weighted;

        const cell = document.getElementById(`weighted-${index}`);
        if (cell) cell.textContent = weighted.toFixed(2);
    });

    document.getElementById("totalScore").textContent = total.toFixed(2);
    const hiddenTotal = document.getElementById("hiddenTotalScore");
    if (hiddenTotal) hiddenTotal.value = total.toFixed(2);
}

// ── Validate before form submits ─────────────────────────────────────────────
function validateForm() {
    if (!document.getElementById("hiddenStudentId").value) {
        alert("Please select a student before saving.");
        return false;
    }

    const selects = document.querySelectorAll(".score-select");
    for (const sel of selects) {
        if (!sel.value) {
            alert("Please fill in a score for every component before saving.");
            sel.focus();
            return false;
        }
    }

    // Sync total into hidden field just before submit
    const total = document.getElementById("totalScore").textContent;
    document.getElementById("hiddenTotalScore").value = parseFloat(total) || 0;

    return true;
}

// ── Reset form to initial state ──────────────────────────────────────────────
function resetForm() {
    document.getElementById("studentSelect").value       = "";
    document.getElementById("roleDisplay").textContent   = "— detected automatically —";
    document.getElementById("hiddenRole").value          = "";
    document.getElementById("hiddenStudentId").value     = "";
    document.getElementById("hiddenInternshipId").value  = "";
    document.getElementById("assessmentBody").innerHTML  = "";
    document.getElementById("totalScore").textContent    = "0.00";
    document.getElementById("hiddenTotalScore").value    = "0";
    document.getElementById("assessmentTable").style.display = "none";
    document.getElementById("assessmentTable").removeAttribute("data-internship");
    document.getElementById("submitBtn").style.display   = "none";
}