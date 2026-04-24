function calculateDuration() {
    const startInput = document.getElementById('startDate').value;
    const endInput = document.getElementById('endDate').value;
    const durationInput = document.getElementById('duration');

    if (startInput && endInput) {
        const start = new Date(startInput);
        const end = new Date(endInput);

        const diffInMs = end - start;

        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

        if (diffInDays >= 0) {
            durationInput.value = diffInDays;
        } else {
            durationInput.value = "Invalid range";
        }
    }
}