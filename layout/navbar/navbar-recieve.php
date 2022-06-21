<nav class="navbar sticky-top navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a id="sidebarCollapse" class="nav-item nav-link navbar-btn d-flex align-items-center">
                <svg class="bi me-2" width="23" height="23">
                    <use xlink:href="#sidebar_icon" />
                </svg>
            </a>
            <a id="suppliertablink" class="nav-item nav-link d-flex align-items-center" onclick="javascript: NavbarLinkClick('supplier','/manager/receive/supplier/supplier.php'); ">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#supplier_icon" />
                </svg>
                &nbsp;Suppliers
            </a>
            <a id="producttablink" class="nav-item nav-link d-flex align-items-center" onclick="javascript: NavbarLinkClick('product','/manager/receive/product/product.php'); ">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#product_icon" />
                </svg>
                &nbsp;Products
            </a>
            <a id="deliverytablink" class="nav-item nav-link d-flex align-items-center" onclick="javascript: NavbarLinkClick('delivery','/manager/receive/delivery/delivery.php'); ">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#delivery_icon" />
                </svg>
                &nbsp;Delivery
            </a>
        </div>
    </div>
</nav>