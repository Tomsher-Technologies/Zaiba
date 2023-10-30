<?php
$currentUrl = url()->current();
$brands = \App\Models\Brand::all();
?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href="{{ asset('vendor/harimayco-menu/style.css') }}" rel="stylesheet">
<div id="hwpwrap">
    <div class="custom-wp-admin wp-admin wp-core-ui js   menu-max-depth-0 nav-menus-php auto-fold admin-bar">
        <div id="wpwrap">
            <div id="wpcontent">
                <div id="wpbody">
                    <div id="wpbody-content">

                        <div class="wrap">

                            <div class="manage-menus">
                                <form method="get" action="{{ $currentUrl }}">
                                    <label for="menu" class="selected-menu">Select the menu you want to
                                        edit:</label>

                                    {!! Menu::select('menu', $menulist) !!}

                                    <span class="submit-btn">
                                        <input type="submit" class="button-secondary" value="Choose">
                                    </span>
                                    {{-- <span class="add-new-menu-action"> or <a href="{{ $currentUrl }}?action=edit&menu=0">Create new menu</a>. </span> --}}
                                </form>
                            </div>
                            <div id="nav-menus-frame">

                                @if (request()->has('menu') && !empty(request()->input('menu')))
                                    <div id="menu-settings-column" class="metabox-holder">

                                        <div class="clear"></div>

                                        <form id="nav-menu-meta" action="" class="nav-menu-meta" method="post"
                                            enctype="multipart/form-data">
                                            <div id="side-sortables" class="accordion-container">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section  open add-page"
                                                        id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0"> Custom
                                                            Link <span class="screen-reader-text">Press return or enter
                                                                to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    <p id="menu-item-url-wrap">
                                                                        <label class="howto"
                                                                            for="custom-menu-item-url">
                                                                            <span>URL</span>&nbsp;&nbsp;&nbsp;
                                                                            <input id="custom-menu-item-url"
                                                                                name="url" type="text"
                                                                                class="regular-text menu-item-textbox input-with-default-title"
                                                                                title="URL">
                                                                        </label>
                                                                    </p>

                                                                    <p id="menu-item-name-wrap">
                                                                        <label class="howto"
                                                                            for="custom-menu-item-name">
                                                                            <span>Label</span>&nbsp;
                                                                            <input id="custom-menu-item-name"
                                                                                name="label" type="text"
                                                                                class="regular-text menu-item-textbox input-with-default-title"
                                                                                title="Label menu">
                                                                        </label>
                                                                    </p>

                                                                    @if (!empty($roles))
                                                                        <p id="menu-item-role_id-wrap">
                                                                            <label class="howto"
                                                                                for="custom-menu-item-name">
                                                                                <span>Role</span>&nbsp;
                                                                                <select id="custom-menu-item-role"
                                                                                    name="role">
                                                                                    <option value="0">Select Role
                                                                                    </option>
                                                                                    @foreach ($roles as $role)
                                                                                        <option
                                                                                            value="{{ $role->$role_pk }}">
                                                                                            {{ ucfirst($role->$role_title_field) }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </label>
                                                                        </p>
                                                                    @endif

                                                                    <p class="button-controls">

                                                                        <a href="#" onclick="addcustommenu()"
                                                                            class="button-secondary submit-add-to-menu right">Add
                                                                            menu item</a>
                                                                        <span class="spinner" id="spincustomu"></span>
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                            {{-- <div id="side-sortables" class="accordion-container">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section  open add-page"
                                                        id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0">
                                                            Products <span class="screen-reader-text">Press return or
                                                                enter to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    <p id="product-item-url-wrap">
                                                                        @foreach (allProducts() as $product)
                                                                            <label class="w-100 d-block"
                                                                                for="product-{{ $product->id }}">
                                                                                <input type="checkbox"
                                                                                    name="product_menu"
                                                                                    id="product-{{ $product->id }}"
                                                                                    data-name="{{ $product->name }}"
                                                                                    value="{{ route('product', $product->slug) }}">
                                                                                {{ $product->name }}
                                                                            </label>
                                                                        @endforeach
                                                                    </p>

                                                                    <p class="button-controls">
                                                                        <a href="#" onclick="addProduct()"
                                                                            class="button-secondary  right">Add
                                                                            menu item</a>
                                                                        <span class="spinner"
                                                                            id="spincustomuPro"></span>
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div> --}}
                                            <div id="side-sortables" class="accordion-container">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section  open add-page"
                                                        id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0">
                                                            Categories <span class="screen-reader-text">Press return or
                                                                enter to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    <p id="category-item-url-wrap">
                                                                        @foreach (getAllCategories() as $caterory)
                                                                            <label class="w-100 d-block"
                                                                                for="category-{{ $caterory->id }}">
                                                                                <input type="checkbox"
                                                                                    name="product_menu"
                                                                                    id="category-{{ $caterory->id }}"
                                                                                    data-name="{{ $caterory->name }}"
                                                                                    value="{{ route('products.category', $caterory->slug) }}">
                                                                                {{ $caterory->name }}
                                                                            </label>
                                                                            @if ($caterory->child->count())
                                                                                @foreach ($caterory->child as $item)
                                                                                    <label class="w-100 d-block"
                                                                                        for="category-{{ $item->id }}">
                                                                                        <input type="checkbox"
                                                                                            name="product_menu"
                                                                                            id="category-{{ $item->id }}"
                                                                                            data-name="{{ $item->name }}"
                                                                                            value="{{ route('products.category', $item->slug) }}">
                                                                                        -- {{ $item->name }}
                                                                                    </label>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    </p>

                                                                    <p class="button-controls">
                                                                        <a href="#" onclick="addCategory()"
                                                                            class="button-secondary right">Add
                                                                            menu item</a>
                                                                        <span class="spinner"
                                                                            id="spincustomuCat"></span>
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </form>

                                    </div>
                                @endif
                                <div id="menu-management-liquid">
                                    <div id="menu-management">
                                        <form id="update-nav-menu" action="" method="post"
                                            enctype="multipart/form-data">
                                            <div class="menu-edit ">
                                                <div id="nav-menu-header">
                                                    <div class="major-publishing-actions">
                                                        <label class="menu-name-label howto open-label"
                                                            for="menu-name">
                                                            <span>Name</span>
                                                            <input name="menu-name" id="menu-name" type="text"
                                                                class="menu-name regular-text menu-item-textbox"
                                                                title="Enter menu name"
                                                                value="@if (isset($indmenu)) {{ $indmenu->name }} @endif">
                                                            <input type="hidden" id="idmenu"
                                                                value="@if (isset($indmenu)) {{ $indmenu->id }} @endif" />
                                                        </label>

                                                        @if (request()->has('action'))
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                    id="save_menu_header"
                                                                    class="button button-primary menu-save">Create
                                                                    menu</a>
                                                            </div>
                                                        @elseif(request()->has('menu'))
                                                            <div class="publishing-action">
                                                                <a onclick="getmenus()" name="save_menu"
                                                                    id="save_menu_header"
                                                                    class="button button-primary menu-save">Save
                                                                    menu</a>
                                                                <span class="spinner" id="spincustomu2"></span>
                                                            </div>
                                                        @else
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                    id="save_menu_header"
                                                                    class="button button-primary menu-save">Create
                                                                    menu</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div id="post-body">
                                                    <div id="post-body-content">

                                                        @if (request()->has('menu'))
                                                            <h3>Menu Structure</h3>
                                                            <div class="drag-instructions post-body-plain"
                                                                style="">
                                                                <p>
                                                                    Place each item in the order you prefer. Click on
                                                                    the arrow to the right of the item to display more
                                                                    configuration options.
                                                                </p>
                                                            </div>
                                                        @else
                                                            <h3>Menu Creation</h3>
                                                            <div class="drag-instructions post-body-plain"
                                                                style="">
                                                                <p>
                                                                    Please enter the name and select "Create menu"
                                                                    button
                                                                </p>
                                                            </div>
                                                        @endif

                                                        <ul class="menu ui-sortable" id="menu-to-edit">
                                                            @if (isset($menus))
                                                                @foreach ($menus as $m)
                                                                    <li id="menu-item-{{ $m->id }}"
                                                                        class="menu-item menu-item-depth-{{ $m->depth }} menu-item-page menu-item-edit-inactive pending"
                                                                        style="display: list-item;">
                                                                        <dl class="menu-item-bar">
                                                                            <dt class="menu-item-handle">
                                                                                <span class="item-title">
                                                                                    <input type="checkbox"
                                                                                        name="bulkDelete"
                                                                                        value="{{ $m->id }}">
                                                                                    <span class="menu-item-title">
                                                                                        <span
                                                                                            id="menutitletemp_{{ $m->id }}">{{ $m->label }}</span>
                                                                                        <span
                                                                                            style="color: transparent;">|{{ $m->id }}|</span>
                                                                                    </span> <span class="is-submenu"
                                                                                        style="@if ($m->depth == 0) display: none; @endif">Subelement</span>
                                                                                </span>
                                                                                <span class="item-controls"> <span
                                                                                        class="item-type">Link</span>
                                                                                    <span
                                                                                        class="item-order hide-if-js">
                                                                                        <a href="{{ $currentUrl }}?action=move-up-menu-item&menu-item={{ $m->id }}&_wpnonce=8b3eb7ac44"
                                                                                            class="item-move-up"><abbr
                                                                                                title="Move Up">↑</abbr></a>
                                                                                        | <a href="{{ $currentUrl }}?action=move-down-menu-item&menu-item={{ $m->id }}&_wpnonce=8b3eb7ac44"
                                                                                            class="item-move-down"><abbr
                                                                                                title="Move Down">↓</abbr></a>
                                                                                    </span> <a class="item-edit"
                                                                                        id="edit-{{ $m->id }}"
                                                                                        title=" "
                                                                                        href="{{ $currentUrl }}?edit-menu-item={{ $m->id }}#menu-item-settings-{{ $m->id }}">
                                                                                    </a> </span>
                                                                            </dt>
                                                                        </dl>

                                                                        <div class="menu-item-settings"
                                                                            id="menu-item-settings-{{ $m->id }}">
                                                                            <input type="hidden"
                                                                                class="edit-menu-item-id"
                                                                                name="menuid_{{ $m->id }}"
                                                                                value="{{ $m->id }}" />

                                                                            <div class="row">
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        for="edit-menu-item-title-{{ $m->id }}">
                                                                                        Label
                                                                                        <br>
                                                                                        <input type="text"
                                                                                            id="idlabelmenu_{{ $m->id }}"
                                                                                            class="widefat edit-menu-item-title"
                                                                                            name="idlabelmenu_{{ $m->id }}"
                                                                                            value="{{ $m->label }}">
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        for="edit-menu-item-classes-{{ $m->id }}">
                                                                                        Class CSS (optional)
                                                                                        <br>
                                                                                        <input type="text"
                                                                                            id="clases_menu_{{ $m->id }}"
                                                                                            class="widefat code edit-menu-item-classes"
                                                                                            name="clases_menu_{{ $m->id }}"
                                                                                            value="{{ $m->class }}">
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        for="edit-menu-item-url-{{ $m->id }}">
                                                                                        Url
                                                                                        <br>
                                                                                        <input type="text"
                                                                                            id="url_menu_{{ $m->id }}"
                                                                                            class="widefat code edit-menu-item-url"
                                                                                            id="url_menu_{{ $m->id }}"
                                                                                            value="{{ $m->link }}">
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <p
                                                                                        class="field-move hide-if-no-js description description-wide">
                                                                                        <label> <span>Move</span> <a
                                                                                                href="{{ $currentUrl }}"
                                                                                                class="menus-move-up"
                                                                                                style="display: none;">Move
                                                                                                up</a> <a
                                                                                                href="{{ $currentUrl }}"
                                                                                                class="menus-move-down"
                                                                                                title="Mover uno abajo"
                                                                                                style="display: inline;">Move
                                                                                                Down</a> <a
                                                                                                href="{{ $currentUrl }}"
                                                                                                class="menus-move-left"
                                                                                                style="display: none;"></a>
                                                                                            <a href="{{ $currentUrl }}"
                                                                                                class="menus-move-right"
                                                                                                style="display: none;"></a>
                                                                                            <a href="{{ $currentUrl }}"
                                                                                                class="menus-move-top"
                                                                                                style="display: none;">Top</a>
                                                                                        </label>
                                                                                    </p>
                                                                                </div>
                                                                            </div>


                                                                            @if ($m->depth == 0 && $indmenu->id == 1)
                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 1
                                                                                        <small>(490x664)</small>
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <div class="input-group"
                                                                                            data-toggle="aizuploader"
                                                                                            data-type="image">
                                                                                            <div
                                                                                                class="input-group-prepend">
                                                                                                <div
                                                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                                                    Browse
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-control file-amount">
                                                                                                Choose File</div>
                                                                                            <input
                                                                                                value="{{ old('img_1', $m->img_1) }}"
                                                                                                type="hidden"
                                                                                                name="img_1"
                                                                                                id="img_1_{{ $m->id }}"
                                                                                                class="selected-files img_1"
                                                                                                required>
                                                                                        </div>
                                                                                        <div
                                                                                            class="file-preview box sm">
                                                                                        </div>
                                                                                        @error('img_1')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 1 Link
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <input
                                                                                            value="{{ old('img_1_link', $m->img_1_link) }}"
                                                                                            type="url"
                                                                                            id="img_1_link_{{ $m->id }}"
                                                                                            name="img_1_link"
                                                                                            class="widefat w-100 img_1_link "
                                                                                            required>

                                                                                        @error('img_1_link')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 2
                                                                                        <small>(390x190)</small>
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <div class="input-group"
                                                                                            data-toggle="aizuploader"
                                                                                            data-type="image">
                                                                                            <div
                                                                                                class="input-group-prepend">
                                                                                                <div
                                                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                                                    Browse
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-control file-amount">
                                                                                                Choose File</div>
                                                                                            <input
                                                                                                value="{{ old('img_2', $m->img_2) }}"
                                                                                                type="hidden"
                                                                                                name="img_2"
                                                                                                id="img_2_{{ $m->id }}"
                                                                                                class="selected-files img_2"
                                                                                                required>
                                                                                        </div>
                                                                                        <div
                                                                                            class="file-preview box sm">
                                                                                        </div>
                                                                                        @error('img_2')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 2 Link
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <input
                                                                                            value="{{ old('img_2_link', $m->img_2_link) }}"
                                                                                            type="url"
                                                                                            name="img_2_link"
                                                                                            id="img_2_link_{{ $m->id }}"
                                                                                            class="widefat w-100 img_2_link"
                                                                                            required>

                                                                                        @error('img_2_link')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 3
                                                                                        <small>(390x190)</small>
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <div class="input-group"
                                                                                            data-toggle="aizuploader"
                                                                                            data-type="image">
                                                                                            <div
                                                                                                class="input-group-prepend">
                                                                                                <div
                                                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                                                    Browse
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-control file-amount">
                                                                                                Choose File</div>
                                                                                            <input
                                                                                                value="{{ old('img_3', $m->img_3) }}"
                                                                                                type="hidden"
                                                                                                name="img_3"
                                                                                                id="img_3_{{ $m->id }}"
                                                                                                class="selected-files img_3"
                                                                                                required>
                                                                                        </div>
                                                                                        <div
                                                                                            class="file-preview box sm">
                                                                                        </div>
                                                                                        @error('img_3')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 3 Link
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <input
                                                                                            value="{{ old('img_3_link', $m->img_3_link) }}"
                                                                                            type="url"
                                                                                            id="img_3_link_{{ $m->id }}"
                                                                                            name="img_3_link"
                                                                                            class="widefat w-100 img_3_link"
                                                                                            required>

                                                                                        @error('img_3_link')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Brands
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <select
                                                                                            class="form-control aiz-selectpicker"
                                                                                            name="brand_id"
                                                                                            id="brand_id_{{ $m->id }}"
                                                                                            data-live-search="true"
                                                                                            multiple>
                                                                                            <option value="">
                                                                                                Select Brand</option>
                                                                                            @foreach ($brands as $brand)
                                                                                                <option
                                                                                                    {{ in_array($brand->id, explode(',', $m->brands)) ? 'selected' : '' }}
                                                                                                    value="{{ $brand->id }}">
                                                                                                    {{ $brand->name }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if ($m->depth == 0 && $indmenu->id == 6)
                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 1
                                                                                        <small>(490x664)</small>
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <div class="input-group"
                                                                                            data-toggle="aizuploader"
                                                                                            data-type="image">
                                                                                            <div
                                                                                                class="input-group-prepend">
                                                                                                <div
                                                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                                                    Browse
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-control file-amount">
                                                                                                Choose File</div>
                                                                                            <input
                                                                                                value="{{ old('img_1', $m->img_1) }}"
                                                                                                type="hidden"
                                                                                                name="img_1"
                                                                                                id="img_1_{{ $m->id }}"
                                                                                                class="selected-files img_1"
                                                                                                required>
                                                                                        </div>
                                                                                        <div
                                                                                            class="file-preview box sm">
                                                                                        </div>
                                                                                        @error('img_1')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group row">
                                                                                    <label
                                                                                        class="col-md-3 col-form-label"
                                                                                        for="signinSrEmail">
                                                                                        Banner 1 Link
                                                                                    </label>
                                                                                    <div class="col-md-9">
                                                                                        <input
                                                                                            value="{{ old('img_1_link', $m->img_1_link) }}"
                                                                                            type="url"
                                                                                            id="img_1_link_{{ $m->id }}"
                                                                                            name="img_1_link"
                                                                                            class="widefat w-100 img_1_link "
                                                                                            required>

                                                                                        @error('img_1_link')
                                                                                            <div
                                                                                                class="alert alert-danger">
                                                                                                {{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            @endif


                                                                            @if (!empty($roles))
                                                                                <p
                                                                                    class="field-css-role description description-wide">
                                                                                    <label
                                                                                        for="edit-menu-item-role-{{ $m->id }}">
                                                                                        Role
                                                                                        <br>
                                                                                        <select
                                                                                            id="role_menu_{{ $m->id }}"
                                                                                            class="widefat code edit-menu-item-role"
                                                                                            name="role_menu_[{{ $m->id }}]">
                                                                                            <option value="0">
                                                                                                Select Role</option>
                                                                                            @foreach ($roles as $role)
                                                                                                <option
                                                                                                    @if ($role->id == $m->role_id) selected @endif
                                                                                                    value="{{ $role->$role_pk }}">
                                                                                                    {{ ucwords($role->$role_title_field) }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </label>
                                                                                </p>
                                                                            @endif



                                                                            <div
                                                                                class="menu-item-actions description-wide submitbox">

                                                                                <a class="item-delete submitdelete deletion"
                                                                                    id="delete-{{ $m->id }}"
                                                                                    href="{{ $currentUrl }}?action=delete-menu-item&menu-item={{ $m->id }}&_wpnonce=2844002501">Delete</a>
                                                                                <span class="meta-sep hide-if-no-js"> |
                                                                                </span>
                                                                                <a class="item-cancel submitcancel hide-if-no-js button-secondary"
                                                                                    id="cancel-{{ $m->id }}"
                                                                                    href="{{ $currentUrl }}?edit-menu-item={{ $m->id }}&cancel=1424297719#menu-item-settings-{{ $m->id }}">Cancel</a>
                                                                                <span class="meta-sep hide-if-no-js"> |
                                                                                </span>
                                                                                <a onclick="getmenus()"
                                                                                    class="button button-primary updatemenu"
                                                                                    id="update-{{ $m->id }}"
                                                                                    href="javascript:void(0)">Update
                                                                                    item</a>

                                                                                @if ($m->depth == 0 && ($indmenu->id == 1 || $indmenu->id == 6))
                                                                                    <a onclick="updateMenu({{ $m->id }})"
                                                                                        class="button button-primary"
                                                                                        href="javascript:void(0)">Update
                                                                                        images</a>
                                                                                @endif
                                                                            </div>

                                                                        </div>
                                                                        <ul class="menu-item-transport"></ul>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                        <div class="menu-settings">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="nav-menu-footer">
                                                    <div class="major-publishing-actions">

                                                        @if (request()->has('action'))
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                    id="save_menu_header"
                                                                    class="button button-primary menu-save">Create
                                                                    menu</a>
                                                            </div>
                                                        @elseif(request()->has('menu'))
                                                            <span class="delete-action"> <a
                                                                    class="button button-danger"
                                                                    onclick="deleteitems()"
                                                                    href="javascript:void(9)">Delete selected items</a>
                                                            </span>
                                                            {{-- <span class="delete-action"> <a
                                                                    class="submitdelete deletion menu-delete"
                                                                    onclick="deletemenu()"
                                                                    href="javascript:void(9)">Delete menu</a> </span> --}}
                                                            <div class="publishing-action">

                                                                <a onclick="getmenus()" name="save_menu"
                                                                    id="save_menu_header"
                                                                    class="button button-primary menu-save">Save
                                                                    menu</a>
                                                                <span class="spinner" id="spincustomu2"></span>
                                                            </div>
                                                        @else
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu"
                                                                    id="save_menu_header"
                                                                    class="button button-primary menu-save">Create
                                                                    menu</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
</div>
<script>
    function deleteitems() {
        var refresh = false

        var count = 0;
        var c_count = 0;

        $('.menu-item  input:checkbox[name=bulkDelete]').each(function() {
            if (this.checked) {
                refresh = true
                count++;
                $.ajax({
                    dataType: 'json',
                    data: {
                        id: $(this).val()
                    },
                    url: deleteitemmenur,
                    type: 'POST',
                    complete: function(response) {
                        c_count++;
                        if ((c_count == count)) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
        if (refresh) {
            // 
        }
    }

    function addProduct() {
        $('#spincustomuPro').show();

        $('#product-item-url-wrap input:checkbox').each(function() {
            if (this.checked) {
                $.ajax({
                    data: {
                        labelmenu: $(this).data('name'),
                        linkmenu: $(this).val(),
                        rolemenu: $('#custom-menu-item-role').val(),
                        idmenu: $('#idmenu').val()
                    },
                    url: addcustommenur,
                    type: 'POST'
                });
            }
        });

        $('#spincustomuPro').hide();
        window.location.reload();
    }

    function addCategory() {
        $('#spincustomuCat').show();

        $('#category-item-url-wrap input:checkbox').each(function() {
            if (this.checked) {
                $.ajax({
                    data: {
                        labelmenu: $(this).data('name'),
                        linkmenu: $(this).val(),
                        rolemenu: $('#custom-menu-item-role').val(),
                        idmenu: $('#idmenu').val()
                    },
                    url: addcustommenur,
                    type: 'POST'
                });
            }
        });

        $('#spincustomuCat').hide();
        window.location.reload();
    }
</script>
<style>
    #category-item-url-wrap,
    #product-item-url-wrap {
        max-height: 500px;
        overflow-y: scroll;
    }
</style>
