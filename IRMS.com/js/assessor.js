const components = [
    { name: "Undertaking Tasks/Projects", weight: 10, desc: "Ability to undertake and complete assigned tasks." },
    { name: "Health and Safety Requirements at the Workplace", weight: 10, desc: "Adherence to health and safety standards." },
    { name: "Connectivity and Use of Theoretical Knowledge", weight: 10, desc: "Application of academic knowledge in practice." },
    { name: "Presentation of the Report as a Written Document", weight: 15, desc: "Quality, structure, and clarity of written report." },
    { name: "Clarity of Language and Illustration", weight: 10, desc: "Clear communication and use of visuals." },
    { name: "Lifelong Learning Activities", weight: 15, desc: "Engagement in self-directed learning." },
    { name: "Project Management", weight: 15, desc: "Planning, organizing, and managing project tasks." },
    { name: "Time Management", weight: 15, desc: "Punctuality, deadlines, and efficient use of time." }
];

let currentRole = "";
let scores = {};

function buildAssessmentTable() {
    const tbody = document.getElementById("assessmentBody");
    tbody.innerHTML = "";

    components.forEach((comp, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="comp-cell">
                <div class="comp-name">${comp.name}</div>
                <div class="comp-desc">${comp.desc}</div>
            </td>
            <td class="weight-cell">
                <span class="weight-badge">${comp.weight}%</span>
                <div class="weight-label">relative weight</div>
            </td>
            <td class="score-cell">
                <div class="score-buttons" id="scoreButtons-${index}">
                    ${[1,2,3,4,5].map(n => `
                        <button type="button" class="score-btn" onclick="selectScore(${index}, ${n})">${n}</button>
                    `).join("")}
                </div>
            </td>
            <td class="weighted-cell" id="weighted-${index}">
                <span class="weighted-value">—</span>
            </td>
            <td class="note-cell">
                <textarea class="eval-note" placeholder="Add evaluation notes..."></textarea>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function selectScore(index, value) {
    scores[index] = value;

    // Update button styles
    const buttons = document.querySelectorAll(`#scoreButtons-${index} .score-btn`);
    buttons.forEach(btn => {
        btn.classList.remove("selected");
        if (parseInt(btn.textContent) === value) {
            btn.classList.add("selected");
        }
    });

    // Update weighted contribution
    const weight = components[index].weight;
    const weighted = ((value / 5) * weight).toFixed(1);
    const weightedCell = document.getElementById(`weighted-${index}`);
    weightedCell.innerHTML = `<span class="weighted-value active">${weighted}%</span><span class="weighted-max"> / ${weight}%</span>`;

    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    components.forEach((comp, index) => {
        if (scores[index]) {
            total += (scores[index] / 5) * comp.weight;
        }
    });
    document.getElementById("totalScore").textContent = total.toFixed(2);
}

function updateRole() {
    currentRole = document.getElementById("roleSelect").value;
    const table = document.getElementById("assessmentTable");
    const submitBtn = document.getElementById("submitBtn");

    if (!currentRole) {
        table.style.display = "none";
        submitBtn.style.display = "none";
        return;
    }

    table.style.display = "table";
    submitBtn.style.display = "inline-block";
}

function resetForm() {
    scores = {};
    document.querySelectorAll(".score-btn").forEach(btn => btn.classList.remove("selected"));
    document.querySelectorAll(".eval-note").forEach(t => t.value = "");
    document.querySelectorAll(".weighted-value").forEach(el => {
        el.textContent = "—";
        el.classList.remove("active");
    });
    document.querySelectorAll(".weighted-max").forEach(el => el.remove());
    document.getElementById("studentSelect").value = "";
    document.getElementById("roleSelect").value = "";
    document.getElementById("totalScore").textContent = "0.00";
    document.getElementById("assessmentTable").style.display = "none";
    document.getElementById("submitBtn").style.display = "none";
    currentRole = "";
}

function submitAssessment() {
    const studentId = document.getElementById("studentSelect").value;
    const role = document.getElementById("roleValue").value;
    const internshipId = document.getElementById("assessmentTable").getAttribute("data-internship");

    if (!studentId) { alert("Please select a student"); return; }
    if (!role) { alert("Role could not be detected"); return; }

    const rows = document.querySelectorAll("#assessmentBody tr");
    let data = [];

    rows.forEach((row, index) => {
        const comment = row.querySelector(".eval-note").value;
        data.push({
            component: components[index].name,
            score: scores[index] || 0,
            comment: comment
        });
    });

    fetch("submitmark.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            student_id: studentId,
            internship_id: internshipId,
            role: role,
            assessment: data,
            total: document.getElementById("totalScore").textContent
        })
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert("Assessment saved successfully!");
        } else {
            alert("Error: " + result.message);
        }
    })
    .catch(err => {
        alert("Submission failed: " + err);
    });
}
