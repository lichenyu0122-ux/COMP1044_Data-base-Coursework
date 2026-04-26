function confirmDelete() {
    return confirm("Are you sure you want to delete this record?");
}

function validateScores() {
    let inputs = document.querySelectorAll("input[type='number']");
    
    for (let input of inputs) {
        let value = parseFloat(input.value);

        if (value < 0 || value > 100 || isNaN(value)) {
            alert("Score must be between 0 and 100");
            input.focus();
            return false;
        }
    }

    return true;
}

document.addEventListener("DOMContentLoaded", function () {
    let links = document.querySelectorAll(".nav-links a");
    let current = window.location.href;

    links.forEach(link => {
        if (link.href === current) {
            link.style.color = "#f87171";
        }
    });
});