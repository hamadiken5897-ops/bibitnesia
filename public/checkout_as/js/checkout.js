let currentStep = 1;

function nextStep() {
    document.getElementById(`step${currentStep}`).classList.add("hidden");
    document.getElementById(`circle${currentStep}`).classList.remove("active");

    currentStep++;
    document.getElementById(`step${currentStep}`).classList.remove("hidden");
    document.getElementById(`circle${currentStep}`).classList.add("active");

    document.getElementById("progressLine").style.width =
        ((currentStep - 1) / 2) * 100 + "%";
}

function prevStep() {
    document.getElementById(`step${currentStep}`).classList.add("hidden");
    document.getElementById(`circle${currentStep}`).classList.remove("active");

    currentStep--;
    document.getElementById(`step${currentStep}`).classList.remove("hidden");
    document.getElementById(`circle${currentStep}`).classList.add("active");

    document.getElementById("progressLine").style.width =
        ((currentStep - 1) / 2) * 100 + "%";
}
const provinsiSelect = document.getElementById("provinsi");
const ongkirText = document.getElementById("ongkirText");
const ongkirValue = document.getElementById("ongkirValue");

provinsiSelect?.addEventListener("change", function () {
    const selected = this.options[this.selectedIndex];
    const ongkir = selected.dataset.ongkir || 0;

    ongkirText.innerText = "Rp " + parseInt(ongkir).toLocaleString("id-ID");
    ongkirValue.value = ongkir;

    updateSummary(); // kalau kamu pakai ringkasan di kanan
});
