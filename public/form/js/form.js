let currentStep = 1;

function showStep(step) {
    // Semua step
    document.querySelectorAll(".step").forEach((s) => s.classList.add("hidden"));

    // Step aktif
    document.getElementById("step" + step).classList.remove("hidden");

    currentStep = step;
    updateProgress();
    updateRoleFields();
}

// Tombol next
function nextStep() {
    if (currentStep < 3) {
        showStep(currentStep + 1);
    }
}

// Tombol previous
function prevStep() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
}

// Progress bar & circle
function updateProgress() {
    const line = document.getElementById("progressLine");
    const circles = [
        document.getElementById("circle1"),
        document.getElementById("circle2"),
        document.getElementById("circle3")
    ];

    // Isi bar
    if (currentStep === 1) line.style.width = "0%";
    if (currentStep === 2) line.style.width = "50%";
    if (currentStep === 3) line.style.width = "100%";

    // Step circle
    circles.forEach((circle, i) => {
        if (i + 1 <= currentStep) {
            circle.classList.add("active");
            circle.classList.add("completed");
        } else {
            circle.classList.remove("active");
            circle.classList.remove("completed");
        }
    });
}

// Menampilkan field penjual / kurir
function updateRoleFields() {
    const role = document.getElementById("role_pengajuan")
        ? document.getElementById("role_pengajuan").value
        : document.querySelector("input[name='role_pengajuan']").value;

    document.getElementById("penjualFields").classList.add("hidden");
    document.getElementById("kurirFields").classList.add("hidden");

    if (currentStep === 3) {
        if (role === "penjual") {
            document.getElementById("penjualFields").classList.remove("hidden");
        } else if (role === "kurir") {
            document.getElementById("kurirFields").classList.remove("hidden");
        }
    }
}

// Start default
showStep(1);
