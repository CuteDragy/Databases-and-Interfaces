function calculateDuration() {
    const startInput = document.getElementById('startDate').value;
    const endInput = document.getElementById('endDate').value;
    const durationInput = document.getElementById('duration');

    if (startInput && endInput) {
        const start = new Date(startInput);
        const end = new Date(endInput);

        // Calculate the difference in milliseconds
        const diffInMs = end - start;

        // Convert milliseconds to days
        // 1 day = 24 hours * 60 mins * 60 secs * 1000 ms
        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

        if (diffInDays >= 0) {
            durationInput.value = diffInDays;
        } else {
            durationInput.value = "Invalid range";
        }
    }
}