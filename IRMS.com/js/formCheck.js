function setError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorSpan = document.getElementById("error-" + fieldId);
    if (field) field.classList.add("input-error");
    if (errorSpan) errorSpan.textContent = message;
}

function clearError(fieldId) {
    const field = document.getElementById(fieldId);
    const errorSpan = document.getElementById("error-" + fieldId);
    if (field) field.classList.remove("input-error");
    if (errorSpan) errorSpan.textContent = "";
}

function validateForm(formId) {
    let isValid = true;

    const form = document.getElementById(formId);
    if (!form) return true;

    const inputs = form.querySelectorAll("input:not([type='radio']):not([type='submit']):not([readonly]):not([type='hidden']), textarea");
    inputs.forEach(function (field) {
        if (field.value.trim() === "") {
            const label = form.querySelector("label[for='" + field.id + "']");
            const labelText = label ? label.textContent.trim() : field.name;
            setError(field.id, labelText + " is required.");
            isValid = false;
        } else {
            clearError(field.id);
        }
    });

    const radioGroups = {};
    form.querySelectorAll("input[type='radio']").forEach(function (radio) {
        if (!radioGroups[radio.name]) {
            radioGroups[radio.name] = radio;
        }
    });

    Object.keys(radioGroups).forEach(function (groupName) {
        const selected = form.querySelector("input[name='" + groupName + "']:checked");
        const containerId = groupName + "-container";  
        if (!selected) {
            setError(groupName, "Please select an option.");
            isValid = false;
        } else {
            clearError(groupName);
        }
    });

    return isValid;
}

document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form[id]");
    forms.forEach(function (form) {
        form.setAttribute("novalidate", true);
        form.addEventListener("submit", function (e) {
            if (!validateForm(form.id)) {
                e.preventDefault();
            }
        });
    });
});