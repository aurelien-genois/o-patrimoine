const accordions = [...document.querySelectorAll(".accordion-btn")];

if (accordions.length > 0) {
    accordions.forEach(function (accordion) {
        accordion?.addEventListener("click", function () {
            this.querySelector('.fa-solid').classList.toggle("-rotate-180");
            const filterForm = this.nextElementSibling;
            console.log(filterForm);
            if (filterForm.classList.contains("max-h-56")) {
                filterForm.classList.remove("max-h-56");
                filterForm.classList.add("max-h-0");
            } else {
                filterForm.classList.remove("max-h-0");
                filterForm.classList.add("max-h-56");
            }
        });
    })
}