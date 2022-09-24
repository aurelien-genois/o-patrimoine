const breakpointMobile = 1024;
let type = '';
const menuDeskContainer = document.getElementById("deskMenu");
const menuMobileContainer = document.getElementById("mobileMenu");
let bodyWidth = window.innerWidth;
const menu = menuMobileContainer.querySelector('.header__menu');
const menuClose = document.getElementById('menuClose');
const menuOpen = document.getElementById('btnMenu');
const navMenu = document.getElementById('navMenu');

// Fonction qui place les menu dans le bon container
const createMenu = (type) => {
    if (type == 'desk') {
        menuDeskContainer.append(menu);
    } else if (type == 'mobile') {
        menuMobileContainer.append(menu);
    }
}

// les action a faire quand on close le menu
const closeMenu = () => {
    menu.style.transform = 'translateX(0)';
    navMenu.classList.remove('open')
}

//get device type
if (bodyWidth >= breakpointMobile) {
    type = 'desk';
} else {
    type = 'mobile';
}

createMenu(type);

window.addEventListener('resize', () => {
    bodyWidth = window.innerWidth;

    if (bodyWidth >= breakpointMobile && type == 'mobile') {
        type = 'desk';
        closeMenu();
        createMenu(type);
        [...menu.querySelectorAll('.sub-menu')].forEach(sub => {
            sub.style.removeProperty('display');
        })
    } else if (bodyWidth < breakpointMobile && type == 'desk') {
        type = 'mobile';
        createMenu(type);
        [...menu.querySelectorAll('.sub-menu')].forEach(sub => {
            sub.style.removeProperty('display');
        })
    }
});

menuOpen?.addEventListener('click', () => {
    if (navMenu.classList.contains("open")) {
        closeMenu();
    } else {
        navMenu.classList.add('open')
    }

})
menuClose?.addEventListener('click', () => {
    navMenu.classList.remove('open')
})


/*===============================*/
/*=== Submenu mobile ===*/
/*===============================*/

// slide quand on va vers le submenu
document.querySelectorAll('.menu-item-has-children').forEach(box => {
    let leftArrow = document.createElement('p');
    leftArrow.classList.add('lg:hidden'); // only display on mobile
    leftArrow.textContent = '>';
    leftArrow.addEventListener('click', (e) => {
        menu.style.transform = 'translateX(-100%)';
        box.querySelector('.sub-menu').style.display = 'block';
    });
    box.append(leftArrow);
});

// retour au lvl 0 du menu
document.querySelectorAll('.sub-menu-back').forEach(box => {
    box.addEventListener('click', (e) => {
        menu.style.transform = 'translateX(0)';
        [...menu.querySelectorAll('.sub-menu')].forEach(sub => {
            sub.style.display = 'none';
        })

    });
});


/*===============================*/
/*=== Swipe avec Touch Events ===*/
/*===============================*/
//https://blog.internet-formation.fr/2019/01/creer-un-menu-lateral-swipe-menu-avec-touch-events-et-click-en-css-3-et-javascript-natif/

if (screen.width <= breakpointMobile) {
    let startX = 0; // Position de départ
    let distance = 200; // 200 px de swipe pour afficher le menu

    // Au premier point de contact
    window.addEventListener("touchstart", function (evt) {
        // Récupère les "touches" effectuées
        var touches = evt.changedTouches[0];
        startX = touches.pageX;
        let between = 0;
    }, false);

    // Quand les points de contact sont en mouvement
    window.addEventListener("touchmove", function (evt) {
        // Limite les effets de bord avec le tactile...
        evt.preventDefault();
        evt.stopPropagation();
    }, false);

    // Quand le contact s'arrête
    window.addEventListener("touchend", function (evt) {
        var touches = evt.changedTouches[0];
        var between = touches.pageX - startX;

        // Détection de la direction
        if (between > 0) {
            var orientation = "ltr";
        } else {
            var orientation = "rtl";
        }

        if (Math.abs(between) >= distance && orientation == "ltr" && navMenu.getAttribute("class") != "open") {
            navMenu.classList.add('open')
        }
        if (Math.abs(between) >= distance && orientation == "rtl" && navMenu.getAttribute("class") == "open") {
            navMenu.classList.remove('open')
        }

    }, false);
}