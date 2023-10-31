<!--begin::Title-->
<title>Listes des Affectations | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div
            class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div
                class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Etat d'avancement de mes techniciens</h1>
                <!--end::Title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div
                            class="d-flex align-items-center position-relative my-1">
                            <i
                                class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                    class="path1"></span><span
                                    class="path2"></span></i> <input type="text"
                                data-kt-customer-table-filter="search"
                                id="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Recherche">
                        </div>
                        <!--end::Search-->
                    </div>
            </div>
            <!--end::Info-->
        </div>
    </div>
    <center><%- include('partials/message') %></center>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post"
        data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <!-- <div class="card-header border-0 pt-6"> -->
                    <!--begin::Card title-->
                    <!-- <div class="card-title"> -->
                        <!--begin::Search-->
                        <!-- <div
                            class="d-flex align-items-center position-relative my-1">
                            <i
                                class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                    class="path1"></span><span
                                    class="path2"></span></i> <input type="text"
                                data-kt-customer-table-filter="search"
                                id="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Recherche">
                        </div> -->
                        <!--end::Search-->
                    <!-- </div> -->
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <!-- <div class="card-toolbar"> -->
                        <!--begin::Toolbar-->
                        <!-- <div class="d-flex justify-content-end"
                            data-kt-customer-table-toolbar="base"> -->
                            <!--begin::Filter-->
                            <!-- <div class="w-150px me-3"> -->
                                <!--begin::Select2-->
                                <!-- <select id="select"
                                    class="form-select form-select-solid"
                                    data-control="select2"
                                    data-hide-search="true"
                                    data-placeholder="Etat"
                                    data-kt-ecommerce-order-filter="etat">
                                    <option></option>
                                    <option value="tous">Tous
                                    </option>
                                    <option value="false">
                                        Effectué</option>
                                    <option value="true">
                                        En attente</option>
                                </select> -->
                                <!--end::Select2-->
                            <!-- </div> -->
                            <!--end::Filter-->
                            <!--begin::Export-->
                            <!-- <button type="button" id="excel"
                                class="btn btn-light-primary me-3"
                                data-bs-toggle="modal"
                                data-bs-target="#kt_customers_export_modal">
                                <i class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i> Excel
                            </button> -->
                            <!--end::Export-->
                        <!-- </div> -->
                        <!--end::Toolbar-->
                    <!-- </div> -->
                    <!--end::Card toolbar-->
                <!-- </div> -->
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div id="kt_customers_table_wrapper"
                        class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table aria-describedby=""
                                class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr
                                        class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2 sorting_disabled"
                                            rowspan="1" colspan="1" aria-label="" style="width: 29.8906px;">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    data-kt-check="true"
                                                    data-kt-check-target="#kt_customers_table .form-check-input"
                                                    value="1">
                                            </div>
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">Techniciens</th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">Etat des QCM </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">Etat des Tâches </th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600"
                                id="table">
                                <% users.forEach((user, i) => { %>
                                    <tr class="odd"
                                        etat="<%= user.active %>">
                                        <td>
                                        </td>
                                        <td data-filter="email">
                                            <%= user.firstName + " " + user.lastName %>
                                        </td>
                                        <td data-filter="email">
                                            <div class="d-flex flex-column w-100 me-2 mt-2">
                                                <span class="text-gray-400 me-2 fw-bolder mb-2">
                                                    <%= usersFactuel[i].percentage %>%
                                                </span>
                
                                                <div class="progress bg-light-success w-100 h-5px">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: <%= usersFactuel[i].percentage %>%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-filter="email">
                                            <div class="d-flex flex-column w-100 me-2 mt-2">
                                                <span class="text-gray-400 me-2 fw-bolder mb-2">
                                                    <%= usersDeclaratif[i].percentage %>%
                                                </span>
                
                                                <div class="progress bg-light-success w-100 h-5px">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: <%= usersDeclaratif[i].percentage %>%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                    <!--end::Menu-->
                                    </tr>
                                <% }) %>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label><select
                                            id="kt_customers_table_length"
                                            name="kt_customers_table_length"
                                            class="form-select form-select-sm form-select-solid">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select></label>
                                </div>
                            </div>
                            <div
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination" 
                                        id="kt_customers_table_paginate">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->