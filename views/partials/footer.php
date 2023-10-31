<!--begin::Footer-->
<div class="footer py-4 d-flex flex-lg-column " id="kt_footer">
    <!--begin::Container-->
    <div class=" container-fluid  d-flex flex-column flex-md-row flex-stack">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-semibold me-2">2023&copy;</span>
            <a href="https://keenthemes.com/" class="text-gray-800 text-hover-primary">CFAO
                Mobility Academy Panafrican</a>
        </div>
        <!--end::Copyright-->
    </div>
    <!--end::Footer-->
</div>
<!--end::Wrapper-->
</div>
<!--end::Page-->
</div>
<!--end::Root-->
<!--begin::Modals-->
<!--end::Modals-->
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up"><span class="path1"></span><span class="path2"></span></i>
</div>
<!--end::Scrolltop-->
<!--begin::Javascript-->
<script>
var hostUrl = "../public/assets/index.html";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="../public/assets/plugins/global/plugins.bundle.js"></script>
<script src="../public/assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="../public/assets/plugins/custom/leaflet/leaflet.bundle.js">
</script>
<script src="../public/assets/plugins/custom/datatables/datatables.bundle.js">
</script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="../public/assets/js/widgets.bundle.js"></script>
<script src="../public/assets/js/custom/widgets.js"></script>
<script src="../public/assets/js/custom/apps/chat/chat.js"></script>
<script src="../public/assets/js/custom/utilities/modals/upgrade-plan.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/type.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/budget.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/settings.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/team.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/targets.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/files.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/complete.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-project/main.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/select-location.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/create-app.js">
</script>
<script src="../public/assets/js/custom/utilities/modals/users-search.js">
</script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
</body>
<!--end::Body-->
<!-- Mirrored from preview.keenthemes.com/craft/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 14 Jul 2023 10:41:36 GMT -->

</html>

<script>
const activeMenu = window.location.pathname;
const menuA = document.querySelectorAll("a[class='menu-link']");
const menuSpan = document.querySelectorAll("span[class='menu-link']");


menuA.forEach((link) => {
    const menuPathname = new URL(link.href).pathname;
    if ((activeMenu === menuPathname) || (activeMenu === "/dashboard" && menuPathname === "/dashboard")) {
        link.classList.add("active");
    }
})

$(document).ready(function() {
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table tr").filter(function() {
            $(this).toggle($(this).text()
                .toLowerCase().indexOf(value) >
                -1)
        })
    })

    function addRemoveClass(theRows) {
        theRows.removeClass("odd")
        theRows.filter(":odd").addClass(".odd");
    }

    var rows = $("#table tr");
    addRemoveClass(rows)

    $("#select").on("change", function() {
        var selected = this.value
        if (selected != "tous") {
            rows.filter("[etat=" + selected + "]").show()
            rows.not("[etat=" + selected + "]").hide()
            var visibleRows = rows.filter("[etat=" +
                selected + "]")
            addRemoveClass(visibleRows)
        } else {
            rows.show()
            addRemoveClass(rows)
        }
    })

    $("#excel").on("click", function() {
        let table = document.getElementsByTagName("table");
        debugger;
        TableToExcel.convert(table[0], {
            name: `Table.xlsx`
        })
    });
    // $('#kt_customers_table').DataTable({
    //   "scrollY": "50vh",
    //   "scrollCollapse": true,
    // });
});

// var tab = document.querySelector('table');
// tab.onwheel = function(e) {
//     if (e.deltaY < 0) {
//         tab.style.fontSize = parseInt(getComputedStyle(tab)
//             .fontSize) + 1 + 'px';
//     } else if (e.deltaY > 0) {
//         tab.style.fontSize = parseInt(getComputedStyle(tab)
//                 .fontSize) - 1 + 'px';
//     }
//     e.preventDefault();
// };

var delet = document.querySelector("#delete");
var edit = document.querySelector("#edit");
var password = document.querySelector("#password");
var select = document.querySelector("#etat");
var excel = document.querySelector("#excel");
var questions = document.querySelector("#questions");
var users = document.querySelector("#users");
if (delet) {
    delet.classList.add("disabled")
}
if (edit) {
    edit.classList.add("disabled")
}
if (questions) {
    questions.classList.add("disabled")
}
if (users) {
    users.classList.add("disabled")
}
if (password) {
    password.classList.add("disabled")
}

function enable() {
    var checkbox = document.querySelector("input[type=checkbox]:checked")
    if (checkbox) {
        // select.classList.add("disabled")
        // excel.classList.add("disabled")
        delet.classList.remove("disabled")
        edit.classList.remove("disabled")
        delet.setAttribute("data-bs-target",
            `#kt_modal_desactivate${checkbox.value}`)
        if (edit) {
            edit.setAttribute("data-bs-target",
                `#kt_modal_update_details${checkbox.value}`)
        }
        if (password) {
            password.setAttribute("data-bs-target",
                `#kt_modal_update_password${checkbox.value}`)
            password.classList.remove("disabled")
        }
        if (questions) {
            questions.setAttribute("data-bs-target",
                `#kt_modal_invite_questions${checkbox.value}`)
            questions.classList.remove("disabled")
        }
        if (users) {
            users.setAttribute("data-bs-target",
                `#kt_modal_invite_users${checkbox.value}`)
            users.classList.remove("disabled")
        }
    } else if (checkbox === null) {
        // select.classList.remove("disabled")
        // excel.classList.remove("disabled")
        delet.classList.add("disabled")
        if (edit) {
            edit.classList.add("disabled")
        }
        if (questions) {
            questions.classList.add("disabled")
        }
        if (users) {
            users.classList.add("disabled")
        }
        if (password) {
            password.classList.add("disabled")
        }
    }
}

var paginate = document.querySelector("#kt_customers_table_paginate");
var table_size = document.querySelector("#kt_customers_table_length");
var table = document.querySelector("tbody")
var tr = table.querySelectorAll("tr")
var emptyTable = [];
var index = 1;
let itemPerTable = 10;
let a;

for (let i = 0; i < tr.length; i++) {
    emptyTable.push(tr[i]);
}

table_size.onchange = giveTrPerPage;

function giveTrPerPage() {
    itemPerTable = Number(this.value);
    displayPage(itemPerTable);
    pageGenerator(itemPerTable);
    getElement(itemPerTable);
}

function displayPage(limit) {
    table.innerHTML = "";
    for (let i = 0; i < limit; i++) {
        table.appendChild(emptyTable[i]);
        const pageNum = paginate.querySelectorAll(
            "li[class='paginate_button page-item']");
        pageNum.forEach(n => {
            n.remove()
        })
    }
}
displayPage(itemPerTable);

function pageGenerator(getem) {
    const number = emptyTable.length;
    if (number <= getem) {
        paginate.style.display = "none";
    } else {
        const number_page = Math.ceil(number / getem);
        var array = [];
        for (let i = 1; i <= number_page; i++) {
            array.push(i)
            a = array.map(i => {
                return `<li class="paginate_button page-item" id="number">
                            <a href="#" class="page-link" data-page="${i}">${i}</a>
                            </li>`
            });
            paginate.innerHTML = `
                        <li class="page-item previous"><a
                            href="#" id="prev"
                            class="page-link prev"><i
                            class="previous"></i></a>
                            </li>
                                    ${a}
                                    <li class="page-item next"><a
                                            href="#" id="next"
                                            class="page-link next"><i
                                                class="next"></i></a></li>
                                `
        }
    }
}
pageGenerator(itemPerTable)

let pageLink = paginate.querySelectorAll("a");
let lastPage = pageLink.length - 2;

function pageRunner(page, items, lastPage, active) {
    for (button of page) {
        button.onclick = (e) => {
            e.preventDefault();
            const page_num = e.target.getAttribute("data-page")
            const page_mover = e.target.getAttribute("class");
            if (page_num != null) {
                index = page_num;
            } else {
                if (page_mover === "page-link next" || page_mover ===
                    "next") {
                    index++;
                    if (index >= lastPage) {
                        index = lastPage;
                    }
                } else {
                    index--;
                    if (index <= 1) {
                        index = 1;
                    }
                }
            }
            pageMaker(index, items, active)
        }
    }
}
var pageLi = paginate.querySelectorAll(
    "li[class='paginate_button page-item']");
pageLi[0].classList.add("active");
pageRunner(pageLink, itemPerTable, lastPage, pageLi);

function getElement(val) {
    let pagelink = paginate.querySelectorAll("a");
    let lastpage = pagelink.length - 2;
    let pageli = paginate.querySelectorAll(
        "li[class='paginate_button page-item']");
    pageli[0].classList.add("active");
    pageRunner(pagelink, val, lastpage, pageli)
}

function pageMaker(index, item_per_page, active_page) {
    const start = item_per_page * index;
    const end = start + item_per_page;
    const current_page = emptyTable.slice((start - item_per_page), (end -
        item_per_page));
    table.innerHTML = "";
    for (let i = 0; i < current_page.length; i++) {
        let item = current_page[i];
        table.appendChild(item);
    }
    Array.from(active_page).forEach((e) => {
        e.classList.remove("active");
    });
    active_page[index - 1].classList.add("active");
}
</script>