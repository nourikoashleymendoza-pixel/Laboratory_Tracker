const form = document.getElementById("assetForm");
const modal = document.getElementById("modalOverlay");
const openModalButton = document.getElementById("addAssetModal");
const closeModalButton = document.getElementById("closeModal");
const cancelModalButton = document.getElementById("cancelModal");

function openModal() {
    modal.classList.add("show");
    modal.setAttribute("aria-hidden", "false");
    document.getElementById("name").focus();
}

function closeModal() {
    modal.classList.remove("show");
    modal.setAttribute("aria-hidden", "true");
    form.reset();
    clearErrors();
}

function clearErrors() {
    document.getElementById("nameError").textContent = "";
    document.getElementById("categoryError").textContent = "";
    document.getElementById("quantityError").textContent = "";
}

openModalButton.addEventListener("click", openModal);
closeModalButton.addEventListener("click", closeModal);
cancelModalButton.addEventListener("click", closeModal);

modal.addEventListener("click", function (event) {
    if (event.target === modal) {
        closeModal();
    }
});

document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && modal.classList.contains("show")) {
        closeModal();
    }
});

form.addEventListener("submit", function (event) {
    const itemName = document.querySelector("#name");
    const quantity = document.querySelector("#quantity");
    const category = document.querySelector("#category");

    const nameError = document.querySelector("#nameError");
    const quantityError = document.querySelector("#quantityError");
    const categoryError = document.querySelector("#categoryError");

    clearErrors();
    let isValid = true;

    if (itemName.value.trim() === "") {
        nameError.textContent = "Asset name is required.";
        isValid = false;
    }

    if (category.value === "") {
        categoryError.textContent = "Please select a category.";
        isValid = false;
    }

    if (quantity.value === "" || Number(quantity.value) < 1 || !Number.isInteger(Number(quantity.value))) {
        quantityError.textContent = "Quantity must be a whole number greater than 0.";
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }
});
