const respFilterPlaceBtn = document.getElementById("resp-filter-place");

respFilterPlaceBtn?.addEventListener("click", function () {
    this.querySelector('.fa-solid').classList.toggle("-rotate-180");
    const filterForm = this.nextElementSibling;
    console.log(filterForm);
    if (filterForm.classList.contains("max-h-52")) {
        filterForm.classList.remove("max-h-52");
        filterForm.classList.add("max-h-0");
    } else {
        filterForm.classList.remove("max-h-0");
        filterForm.classList.add("max-h-52");
    }
});