/**
 * assessor.js
 * Handles rendering the assessment criteria table and submitting marks to save_assessment.php
 */

// ── Criteria definitions ─────────────────────────────────────────────────────
// Adjust components and weights to match your actual rubric.
// Weights should sum to 100.

const CRITERIA = {
    lecturer: [
        { component: "Technical Knowledge",        weight: 20 },
        { component: "Problem Solving Skills",     weight: 20 },
        { component: "Communication Skills",       weight: 15 },
        { component: "Professionalism & Attitude", weight: 15 },
        { component: "Report / Documentation",     weight: 15 },
        { component: "Overall Progress",           weight: 15 },
    ],
    supervisor: [
        { component: "Work Quality",               weight: 25 },
        { component: "Punctuality & Discipline",   weight: 15 },
        { component: "Teamwork & Cooperation",     weight: 20 },
        { component: "Initiative",                 weight: 20 },
        { component: "Technical Competency",       weight: 20 },
    ],
};

// ── Render table rows for the selected role ──────────────────────────────────
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
                <select class="score-select" data-index="${index}" onchange="recalculate()">
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
                <textarea class="notes-input" data-index="${index}" rows="2"
                    placeholder="Optional remarks..."></textarea>
            </td>
        `;
        tbody.appendChild(tr);
    });

    recalculate();
}

// ── Recalculate weighted contributions and total ─────────────────────────────
function recalculate() {
    const role = document.getElementById("roleValue").value;
    const rows = CRITERIA[role] || [];
    let total = 0;

    document.querySelectorAll(".score-select").forEach((sel, index) => {
        const score  = parseFloat(sel.value) || 0;
        const weight = rows[index]?.weight || 0;
        // Weighted contribution = (score / 5) * weight
        const weighted = (score / 5) * weight;
        total += weighted;

        const cell = document.getElementById(`weighted-${index}`);
        if (cell) cell.textContent = weighted.toFixed(2);
    });

    document.getElementById("totalScore").textContent = total.toFixed(2);
}

// ── Called by detectRole() in markentry.php after role is resolved ───────────
// Wrap detectRole to also render the table (call this from the inline script)
function onRoleDetected(role) {
    if (role) {
        renderAssessmentTable(role);
        document.getElementById("assessmentTable").style.display = "table";
        document.getElementById("submitBtn").style.display = "inline-block";
    } else {
        document.getElementById("assessmentBody").innerHTML = "";
        document.getElementById("totalScore").textContent = "0.00";
        document.getElementById("assessmentTable").style.display = "none";
        document.getElementById("submitBtn").style.display = "none";
    }
}

// ── Reset form to initial state ──────────────────────────────────────────────
function resetForm() {
    document.getElementById("studentSelect").value = "";
    document.getElementById("roleDisplay").textContent = "— detected automatically —";
    document.getElementById("roleValue").value = "";
    document.getElementById("assessmentBody").innerHTML = "";
    document.getElementById("totalScore").textContent = "0.00";
    document.getElementById("assessmentTable").style.display = "none";
    document.getElementById("assessmentTable").removeAttribute("data-internship");
    document.getElementById("submitBtn").style.display = "none";
}

// ── Collect and submit assessment data ───────────────────────────────────────
async function submitAssessment() {
    const role         = document.getElementById("roleValue").value;
    const internshipId = document.getElementById("assessmentTable").getAttribute("data-internship");
    const totalScore   = parseFloat(document.getElementById("totalScore").textContent);

    // ── Guard: student must be selected ──────────────────────────────────────
    if (!internshipId || !role) {
        alert("Please select a student before saving.");
        return;
    }

    // ── Guard: all scores must be filled ─────────────────────────────────────
    const scoreSelects = document.querySelectorAll(".score-select");
    let allFilled = true;
    scoreSelects.forEach(sel => {
        if (!sel.value) allFilled = false;
    });
    if (!allFilled) {
        alert("Please fill in a score for every component before saving.");
        return;
    }

    // ── Build criteria payload ────────────────────────────────────────────────
    const criteriaRows = CRITERIA[role] || [];
    const criteria = [];

    scoreSelects.forEach((sel, index) => {
        const notesEl = document.querySelectorAll(".notes-input")[index];
        criteria.push({
            component:     criteriaRows[index].component,
            weight:        criteriaRows[index].weight,
            score:         parseInt(sel.value),
            weighted_score: parseFloat(document.getElementById(`weighted-${index}`).textContent),
            notes:         notesEl ? notesEl.value.trim() : "",
        });
    });

    const payload = {
        internship_id: parseInt(internshipId),
        role:          role,
        total_score:   totalScore,
        criteria:      criteria,
    };

    // ── Disable button to prevent double-submit ───────────────────────────────
    const btn = document.getElementById("submitBtn");
    btn.disabled = true;
    btn.textContent = "Saving…";

    try {
        const response = await fetch("save_assessment.php", {
            method:  "POST",
            headers: { "Content-Type": "application/json" },
            body:    JSON.stringify(payload),
        });

        const result = await response.json();

        if (result.success) {
            alert(`Assessment saved!\nAssessment ID: ${result.assessment_id}\nTotal Score: ${result.total_score.toFixed(2)}`);
            // Optionally reset after a successful save:
            // resetForm();
        } else {
            alert("Error: " + result.message);
        }
    } catch (err) {
        alert("Network error. Please try again.\n" + err.message);
    } finally {
        btn.disabled = false;
        btn.textContent = "Save Assessment";
    }
}