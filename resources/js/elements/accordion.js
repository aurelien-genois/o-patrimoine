const respFilterPlaceBtn = document.getElementById("resp-filter-place");

respFilterPlaceBtn?.addEventListener("click", function () {
    this.querySelector('.fa-solid').classList.toggle("-rotate-180");
    var filterForm = this.nextElementSibling;
    if (filterForm.classList.contains("max-h-52")) {
        filterForm.classList.remove("max-h-52");
    } else {
        filterForm.classList.add("max-h-52");
    }
});