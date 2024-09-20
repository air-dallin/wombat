<?php

use App\Http\Controllers\Profile\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect((app()->getLocale() ?: config('app.fallback_locale')) . '/');
})->name('index');

Route::get('/lang/{lang}', '\App\Http\Controllers\Frontend\FrontController@changeLang')
	->middleware('locale')
	->name('change_lang');  // сменить язык клиента

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware'=>['locale'] ], function () {

    Route::get('/', [App\Http\Controllers\Frontend\FrontController::class, 'index'])->name('home');

    Route::get('/contacts', [\App\Http\Controllers\Frontend\FrontController::class, 'contacts'])->name('contacts');
    Route::any('/search', [\App\Http\Controllers\Frontend\FrontController::class, 'search'])->name('search');

    Route::get('/about', [\App\Http\Controllers\Frontend\FrontController::class, 'about'])->name('about');
    Route::get('/policy', [\App\Http\Controllers\Frontend\FrontController::class, 'policy'])->name('policy');
    Route::get('/offer', [\App\Http\Controllers\Frontend\FrontController::class, 'offer'])->name('offer');


    Route::any('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('frontend.login');

    Route::any('/admin/login', [\App\Http\Controllers\Admin\AdminController::class, 'login'])->name('admin.login');
    Route::any('/admin/logout', [\App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('admin.logout');


    // платежные системы
    Route::group(['prefix'=>'payme'], function(){
        Route::any('/init', 'PaymeController@init');
    });

    // сервис
    Route::group(['prefix'=>'cron'], function(){
        Route::get('/companies-init', [\App\Http\Controllers\Frontend\CronController::class, 'companiesInit'])->name('companiesInit');
        Route::get('/documents-create', [\App\Http\Controllers\Frontend\CronController::class, 'documentsCreate'])->name('documentsCreate');
        Route::get('/documents-check', [\App\Http\Controllers\Frontend\CronController::class, 'documentsCheck'])->name('documentsCheck');
        Route::get('/get-documents-kapital', [\App\Http\Controllers\Frontend\CronController::class, 'getDocumentsKapital'])->name('getDocumentsKapital');
        Route::get('/get-documents-didox', [\App\Http\Controllers\Frontend\CronController::class, 'getDocumentsDidox'])->name('getDocumentsDidox');

    });

    Route::any('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect((app()->getLocale() ?: config('app.fallback_locale')) . '/');
    })->name('logout');

    Route::prefix('register')->name('frontend.register.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Frontend\RegisterController::class, 'register'])->name('index');
        Route::any('/complete', [\App\Http\Controllers\Frontend\RegisterController::class, 'complete'])->name('complete');
        Route::get('/{type}', [\App\Http\Controllers\Frontend\RegisterController::class, 'index'])->name('type');
    });

    Route::post('/check-sms-code', [\App\Http\Controllers\Frontend\RegisterController::class, 'checkSmsCode'])->name('check-sms-code');
    Route::post('/send-sms-code', [\App\Http\Controllers\Frontend\RegisterController::class, 'sendSmsCode'])->name('send-sms-code');
    Route::post('/check-phone', [\App\Http\Controllers\Frontend\RegisterController::class, 'checkPhone'])->name('check-phone');

    Route::post('/city/get-cities',[\App\Http\Controllers\Frontend\FrontController::class, 'getCities'])->name('get-cities');
    Route::post('/district/get-districts',[\App\Http\Controllers\Frontend\FrontController::class, 'getDistricts'])->name('get-districts');

    Route::post('/didox/get-timestamp', [\App\Http\Controllers\Profile\DidoxController::class,'getTimestamp'])->name('didox.get-timestamp');
    Route::any('/didox/sign', [\App\Http\Controllers\Profile\DidoxController::class,'sign'])->name('didox.sign');
    Route::any('/didox/reject', [\App\Http\Controllers\Profile\DidoxController::class,'reject'])->name('didox.reject');
    Route::any('/didox/update-token', [\App\Http\Controllers\Profile\DidoxController::class,'updateToken'])->name('didox.update-token');

    Route::group(['namespace' => 'Profile', 'middleware' => ['auth','profile'] ], function () {


        Route::prefix('profile')->name('frontend.profile.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Profile\ProfileController::class, 'info'])->name('info');

            Route::post('/image/destroy/{image}', [\App\Http\Controllers\Profile\ProfileController::class, 'imageDestroy'])->name('image.destroy');

            Route::post('/update/{user}', [\App\Http\Controllers\Profile\ProfileController::class, 'update'])->name('update');

            Route::get('/companies', [\App\Http\Controllers\Profile\CompanyController::class, 'index'])->name('companies.index');
            Route::get('/companies/create', [\App\Http\Controllers\Profile\CompanyController::class, 'create'])->name('companies.create');
            Route::post('/companies/create', [\App\Http\Controllers\Profile\CompanyController::class, 'store'])->name('companies.store');
            Route::get('/companies/edit/{company}', [\App\Http\Controllers\Profile\CompanyController::class, 'edit'])->name('companies.edit');
            Route::post('/companies/update/{company}', [\App\Http\Controllers\Profile\CompanyController::class, 'update'])->name('companies.update');
            Route::post('/companies/destroy/{company}', [\App\Http\Controllers\Profile\CompanyController::class, 'destroy'])->name('companies.destroy');
            Route::post('/companies/get-company-by-inn', [\App\Http\Controllers\Profile\CompanyController::class, 'getCompanyByInn'])->name('companies.get-company-by-inn');
            Route::post('/companies/set-active', [\App\Http\Controllers\Profile\CompanyController::class, 'setActive'])->name('companies.set-active');
            Route::post('/companies/get-info', [\App\Http\Controllers\Profile\CompanyController::class, 'getInfo'])->name('companies.get-info');
            Route::post('/companies/get-ikpu', [\App\Http\Controllers\Profile\CompanyController::class, 'getIkpu'])->name('companies.get-ikpu');
            Route::get('/companies/info/{company}', [\App\Http\Controllers\Profile\CompanyController::class, 'info'])->name('companies.info');
            Route::get('/companies/services', [\App\Http\Controllers\Profile\CompanyController::class, 'services'])->name('companies.services');

            Route::get('/companies/didox/edit/{company}', [\App\Http\Controllers\Profile\DidoxOptionController::class, 'edit'])->name('companies.didox.edit');
            Route::post('/companies/didox/check-didox', [\App\Http\Controllers\Profile\DidoxOptionController::class, 'checkDidox'])->name('companies.check_didox');
            Route::post('/companies/didox/update/{company}', [\App\Http\Controllers\Profile\DidoxOptionController::class, 'update'])->name('companies.didox.update');

            Route::get('/companies/dibank/edit/{dibankOption}', [\App\Http\Controllers\Profile\DibankOptionController::class, 'edit'])->name('companies.dibank.edit');
            Route::post('/companies/dibank/store/{company}', [\App\Http\Controllers\Profile\DibankOptionController::class, 'store'])->name('companies.dibank.store');
            Route::post('/companies/dibank/update/{dibankOption}', [\App\Http\Controllers\Profile\DibankOptionController::class, 'update'])->name('companies.dibank.update');
            Route::post('/companies/dibank/check-dibank', [\App\Http\Controllers\Profile\CompanyController::class, 'checkDibank'])->name('companies.check_dibank');
            Route::post('/companies/dibank/sign-dibank', [\App\Http\Controllers\Profile\CompanyController::class, 'signDibank'])->name('companies.sign_dibank');
            Route::post('/companies/dibank/confirm-dibank', [\App\Http\Controllers\Profile\CompanyController::class, 'confirmDibank'])->name('companies.confirm_dibank');

            Route::get('/companies/kapital/edit/{company}', [\App\Http\Controllers\Profile\KapitalController::class, 'edit'])->name('companies.kapital.edit');
            Route::post('/companies/kapital/check-kapital', [\App\Http\Controllers\Profile\KapitalController::class, 'checkKapital'])->name('companies.check_kapital');
            Route::post('/companies/kapital/update/{company}', [\App\Http\Controllers\Profile\KapitalController::class, 'update'])->name('companies.kapital.update');
            Route::get('/companies/kapital/login', [\App\Http\Controllers\Profile\KapitalController::class, 'login'])->name('companies.kapital.login');


            Route::get('/company_invoice', [\App\Http\Controllers\Profile\CompanyInvoiceController::class, 'index'])->name('company_invoice.index');
            Route::get('/company_invoice/create', [\App\Http\Controllers\Profile\CompanyInvoiceController::class, 'create'])->name('company_invoice.create');
            Route::post('/company_invoice/create', [\App\Http\Controllers\Profile\CompanyInvoiceController::class, 'store'])->name('company_invoice.store');
            Route::get('/company_invoice/edit/{company_invoice}', [\App\Http\Controllers\Profile\CompanyInvoiceController::class, 'edit'])->name('company_invoice.edit');
            Route::post('/company_invoice/update/{company_invoice}', [\App\Http\Controllers\Profile\CompanyInvoiceController::class, 'update'])->name('company_invoice.update');
            Route::post('/company_invoice/destroy/{company_invoice}', [\App\Http\Controllers\Profile\CompanyInvoiceController::class, 'destroy'])->name('company_invoice.destroy');
            Route::post('/company_invoice/set-main-invoice', [\App\Http\Controllers\Profile\CompanyInvoiceController::class, 'setMainInvoice'])->name('company_invoice.set_main_invoice');

            Route::get('/company_casse', [\App\Http\Controllers\Profile\CompanyCasseController::class, 'index'])->name('company_casse.index');
            Route::get('/company_casse/create', [\App\Http\Controllers\Profile\CompanyCasseController::class, 'create'])->name('company_casse.create');
            Route::post('/company_casse/create', [\App\Http\Controllers\Profile\CompanyCasseController::class, 'store'])->name('company_casse.store');
            Route::get('/company_casse/edit/{company_casse}', [\App\Http\Controllers\Profile\CompanyCasseController::class, 'edit'])->name('company_casse.edit');
            Route::post('/company_casse/update/{company_casse}', [\App\Http\Controllers\Profile\CompanyCasseController::class, 'update'])->name('company_casse.update');
            Route::post('/company_casse/destroy/{company_casse}', [\App\Http\Controllers\Profile\CompanyCasseController::class, 'destroy'])->name('company_casse.destroy');

            Route::get('/company_warehouse', [\App\Http\Controllers\Profile\CompanyWarehouseController::class, 'index'])->name('company_warehouse.index');
            Route::get('/company_warehouse/create', [\App\Http\Controllers\Profile\CompanyWarehouseController::class, 'create'])->name('company_warehouse.create');
            Route::post('/company_warehouse/create', [\App\Http\Controllers\Profile\CompanyWarehouseController::class, 'store'])->name('company_warehouse.store');
            Route::get('/company_warehouse/edit/{company_warehouse}', [\App\Http\Controllers\Profile\CompanyWarehouseController::class, 'edit'])->name('company_warehouse.edit');
            Route::post('/company_warehouse/update/{company_warehouse}', [\App\Http\Controllers\Profile\CompanyWarehouseController::class, 'update'])->name('company_warehouse.update');
            Route::post('/company_warehouse/destroy/{company_warehouse}', [\App\Http\Controllers\Profile\CompanyWarehouseController::class, 'destroy'])->name('company_warehouse.destroy');

            Route::get('/company_ikpu', [\App\Http\Controllers\Profile\CompanyIkpuController::class, 'index'])->name('company_ikpu.index');
            Route::get('/company_ikpu/create', [\App\Http\Controllers\Profile\CompanyIkpuController::class, 'create'])->name('company_ikpu.create');
            Route::post('/company_ikpu/create', [\App\Http\Controllers\Profile\CompanyIkpuController::class, 'store'])->name('company_ikpu.store');
            Route::get('/company_ikpu/edit/{company_ikpu}', [\App\Http\Controllers\Profile\CompanyIkpuController::class, 'edit'])->name('company_ikpu.edit');
            Route::post('/company_ikpu/update/{company_ikpu}', [\App\Http\Controllers\Profile\CompanyIkpuController::class, 'update'])->name('company_ikpu.update');
            Route::post('/company_ikpu/destroy/{company_ikpu}', [\App\Http\Controllers\Profile\CompanyIkpuController::class, 'destroy'])->name('company_ikpu.destroy');
            Route::post('/company_ikpu/get-ikpu', [\App\Http\Controllers\Profile\CompanyIkpuController::class, 'getIkpu'])->name('company_ikpu.get-ikpu');

            Route::get('/tarifs', [\App\Http\Controllers\Profile\TarifController::class, 'index'])->name('tarifs.index');
            Route::get('/tarifs/create', [\App\Http\Controllers\Profile\TarifController::class, 'create'])->name('tarifs.create');
            Route::post('/tarifs/create', [\App\Http\Controllers\Profile\TarifController::class, 'store'])->name('tarifs.store');
            Route::get('/tarifs/edit/{tarif}', [\App\Http\Controllers\Profile\TarifController::class, 'edit'])->name('tarifs.edit');
            Route::post('/tarifs/update/{tarif}', [\App\Http\Controllers\Profile\TarifController::class, 'update'])->name('tarifs.update');
            Route::get('/tarifs/payments', [\App\Http\Controllers\Profile\TarifController::class, 'payments'])->name('tarifs.payments');

            Route::get('/company_account/', [\App\Http\Controllers\Profile\CompanyAccountController::class,'index'])->name('company_account.index');
            Route::get('/company_account/accounts/{company}', [\App\Http\Controllers\Profile\CompanyAccountController::class,'accounts'])->name('company_account.accounts');
            Route::get('/company_account/create', [\App\Http\Controllers\Profile\CompanyAccountController::class,'create'])->name('company_account.create');
            Route::post('/company_account/store', [\App\Http\Controllers\Profile\CompanyAccountController::class,'store'])->name('company_account.store');
            Route::get('/company_account/edit/{company_account}', [\App\Http\Controllers\Profile\CompanyAccountController::class,'edit'])->name('company_account.edit');
            Route::post('/company_account/update/{company_account}', [\App\Http\Controllers\Profile\CompanyAccountController::class,'update'])->name('company_account.update');
            Route::get('/company_account/accounts/{company}/{dt}-{ct}', [\App\Http\Controllers\Profile\CompanyAccountController::class,'plan'])->name('company_account.plan');


            Route::post('/wombatai/send-message', [\App\Http\Controllers\Profile\WombatAIController::class,'sendMessage'])->name('wombatai.send_message');

            Route::group(['prefix'=>'modules'], function () {

                Route::get('/modules/casses_order/', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'index'])->name('modules.casses_order.index');
                Route::get('/modules/casses_order/draft', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'draft'])->name('modules.casses_order.draft');
                Route::get('/modules/casses_order/create', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'create'])->name('modules.casses_order.create');
                Route::post('/modules/casses_order/store', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'store'])->name('modules.casses_order.store');
                Route::get('/modules/casses_order/edit/{casses_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'edit'])->name('modules.casses_order.edit');
                Route::post('/modules/casses_order/update/{casses_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'update'])->name('modules.casses_order.update');
                Route::post('/modules/casses_order/destroy/{casses_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'destroy'])->name('modules.casses_order.destroy');

                Route::get('/payment_order/', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'index'])->name('modules.payment_order.index');
                Route::get('/payment_order/draft', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'draft'])->name('modules.payment_order.draft');
                Route::get('/payment_order/create', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'create'])->name('modules.payment_order.create');
                Route::post('/payment_order/store', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'store'])->name('modules.payment_order.store');
                Route::get('/payment_order/edit/{payment_order}', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'edit'])->name('modules.payment_order.edit');
                Route::post('/payment_order/update/{payment_order}', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'update'])->name('modules.payment_order.update');
                Route::post('/payment_order/destroy/{payment_order}', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'destroy'])->name('modules.payment_order.destroy');
                Route::any('/payment_order/invoices', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'getInvoices'])->name('modules.payment_order.invoices');
                Route::any('/payment_order/print/{payment_order}', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'print'])->name('modules.payment_order.print');
                Route::any('/payment_order/download-order/{payment_order}', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'downloadOrder'])->name('modules.payment_order.download_order');
               // Route::any('/payment_order/sign/{payment_order}', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'sign'])->name('modules.payment_order.sign');
                Route::any('/payment_order/get-order', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'getOrder'])->name('modules.payment_order.get_order');
                Route::any('/payment_order/set-movement', [\App\Http\Controllers\Profile\Modules\PaymentOrderController::class,'setMovement'])->name('modules.payment_order.set_movement');

                Route::get('/contract', [\App\Http\Controllers\Profile\Modules\ContractController::class,'index'])->name('modules.contract.index');
                Route::get('/contract/draft', [\App\Http\Controllers\Profile\Modules\ContractController::class,'draft'])->name('modules.contract.draft');
                Route::get('/contract/create', [\App\Http\Controllers\Profile\Modules\ContractController::class,'create'])->name('modules.contract.create');
                Route::post('/contract/store', [\App\Http\Controllers\Profile\Modules\ContractController::class,'store'])->name('modules.contract.store');
                Route::get('/contract/edit/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'edit'])->name('modules.contract.edit');
                Route::get('/contract/view/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'view'])->name('modules.contract.view');
                Route::post('/contract/update/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'update'])->name('modules.contract.update');
                Route::put('/contract/destroy/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'destroy'])->name('modules.contract.destroy');
                Route::post('/contract/invoices', [\App\Http\Controllers\Profile\Modules\ContractController::class,'getInvoices'])->name('modules.contract.invoices');
                Route::post('/contract/get-contracts', [\App\Http\Controllers\Profile\Modules\ContractController::class,'getContracts'])->name('modules.contract.get_contracts');
                Route::post('/contract/get-company-info', [\App\Http\Controllers\Profile\Modules\ContractController::class,'getCompanyInfo'])->name('modules.contract.get_company_info');
                Route::get('/contract/exportPdf/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'exportPdf'])->name('modules.contract.exportPdf');
                Route::get('/contract/print/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'print'])->name('modules.contract.print');
                Route::post('/contract/sign/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'sign'])->name('modules.contract.sign');
                Route::get('/contract/download/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'download'])->name('modules.contract.download');
                Route::get('/contract/check-status/{contract}', [\App\Http\Controllers\Profile\Modules\ContractController::class,'checkStatus'])->name('modules.contract.check-status');

                Route::get('/incoming_order/', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'index'])->name('modules.incoming_order.index');
                Route::get('/incoming_order/draft', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'draft'])->name('modules.incoming_order.draft');
                Route::get('/incoming_order/create', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'create'])->name('modules.incoming_order.create');
                Route::post('/incoming_order/store', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'store'])->name('modules.incoming_order.store');
                Route::get('/incoming_order/edit/{incoming_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'edit'])->name('modules.incoming_order.edit');
                Route::post('/incoming_order/update/{incoming_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'update'])->name('modules.incoming_order.update');
                Route::post('/incoming_order/destroy/{incoming_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'destroy'])->name('modules.incoming_order.destroy');
                Route::post('/incoming_order/casses', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'getCasses'])->name('modules.incoming_order.casses');
                Route::any('/incoming_order/print/{incoming_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'print'])->name('modules.incoming_order.print');
                Route::any('/incoming_order/exportPdf/{incoming_order}', [\App\Http\Controllers\Profile\Modules\IncomingOrderController::class,'exportPdf'])->name('modules.incoming_order.exportPdf');

                Route::get('/expense_order/', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'index'])->name('modules.expense_order.index');
                Route::get('/expense_order/draft', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'draft'])->name('modules.expense_order.draft');
                Route::get('/expense_order/create', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'create'])->name('modules.expense_order.create');
                Route::post('/expense_order/store', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'store'])->name('modules.expense_order.store');
                Route::get('/expense_order/edit/{expense_order}', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'edit'])->name('modules.expense_order.edit');
                Route::post('/expense_order/update/{expense_order}', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'update'])->name('modules.expense_order.update');
                Route::post('/expense_order/destroy/{expense_order}', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'destroy'])->name('modules.expense_order.destroy');
                Route::any('/expense_order/cases', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'getCases'])->name('modules.expense_order.cases');
                Route::any('/expense_order/print/{expense_order}', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'print'])->name('modules.expense_order.print');
                Route::any('/expense_order/exportPdf/{expense_order}', [\App\Http\Controllers\Profile\Modules\ExpenseOrderController::class,'exportPdf'])->name('modules.expense_order.exportPdf');

                Route::get('/product/', [\App\Http\Controllers\Profile\Modules\ProductController::class,'index'])->name('modules.product.index');
                Route::get('/product/draft', [\App\Http\Controllers\Profile\Modules\ProductController::class,'draft'])->name('modules.product.draft');
                Route::get('/product/create', [\App\Http\Controllers\Profile\Modules\ProductController::class,'create'])->name('modules.product.create');
                Route::post('/product/store', [\App\Http\Controllers\Profile\Modules\ProductController::class,'store'])->name('modules.product.store');
                Route::get('/product/edit/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'edit'])->name('modules.product.edit');
                Route::get('/product/view/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'view'])->name('modules.product.view');
                Route::post('/product/update/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'update'])->name('modules.product.update');
                Route::put('/product/destroy/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'destroy'])->name('modules.product.destroy');
                Route::any('/product/nomenklatures', [\App\Http\Controllers\Profile\Modules\ProductController::class,'getNomenklatures'])->name('modules.product.nomenklatures');
                Route::post('/product/sign/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'sign'])->name('modules.product.sign');
                Route::get('/product/print/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'print'])->name('modules.product.print');
                Route::get('/product/check-status/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'checkStatus'])->name('modules.product.check-status');
                Route::get('/product/exportPdf/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'exportPdf'])->name('modules.product.exportPdf');
                Route::get('/product/download/{product}', [\App\Http\Controllers\Profile\Modules\ProductController::class,'download'])->name('modules.product.download');


                Route::get('/guarant/', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'index'])->name('modules.guarant.index');
                Route::get('/guarant/draft', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'draft'])->name('modules.guarant.draft');
                Route::get('/guarant/create', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'create'])->name('modules.guarant.create');
                Route::post('/guarant/store', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'store'])->name('modules.guarant.store');
                Route::get('/guarant/edit/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'edit'])->name('modules.guarant.edit');
                Route::get('/guarant/view/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'view'])->name('modules.guarant.view');
                Route::post('/guarant/update/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'update'])->name('modules.guarant.update');
                Route::put('/guarant/destroy/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'destroy'])->name('modules.guarant.destroy');
                Route::post('/guarant/invoices', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'getInvoices'])->name('modules.guarant.invoices');
                Route::post('/guarant/sign/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'sign'])->name('modules.guarant.sign');
                Route::get('/guarant/check-status/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'checkStatus'])->name('modules.guarant.check-status');
                Route::get('/guarant/print/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'print'])->name('modules.guarant.print');
                Route::get('/guarant/exportPdf/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'exportPdf'])->name('modules.guarant.exportPdf');
                Route::get('/guarant/download/{guarant}', [\App\Http\Controllers\Profile\Modules\GuarantController::class,'download'])->name('modules.guarant.download');

                Route::get('/nomenklature/', [\App\Http\Controllers\Profile\Modules\NomenklatureController::class,'index'])->name('modules.nomenklature.index');
                Route::get('/nomenklature/draft', [\App\Http\Controllers\Profile\Modules\NomenklatureController::class,'draft'])->name('modules.nomenklature.draft');
                Route::get('/nomenklature/create', [\App\Http\Controllers\Profile\Modules\NomenklatureController::class,'create'])->name('modules.nomenklature.create');
                Route::post('/nomenklature/store', [\App\Http\Controllers\Profile\Modules\NomenklatureController::class,'store'])->name('modules.nomenklature.store');
                Route::get('/nomenklature/edit/{nomenklature}', [\App\Http\Controllers\Profile\Modules\NomenklatureController::class,'edit'])->name('modules.nomenklature.edit');
                Route::post('/nomenklature/update/{nomenklature}', [\App\Http\Controllers\Profile\Modules\NomenklatureController::class,'update'])->name('modules.nomenklature.update');
                Route::post('/nomenklature/destroy/{nomenklature}', [\App\Http\Controllers\Profile\Modules\NomenklatureController::class,'destroy'])->name('modules.nomenklature.destroy');

                Route::get('/document/{owner}', [\App\Http\Controllers\Profile\Modules\DocumentController::class,'index'])->name('modules.document.index');
                Route::get('/document/draft/{owner}', [\App\Http\Controllers\Profile\Modules\DocumentController::class,'draft'])->name('modules.document.draft');

                //Route::get('/act', [\App\Http\Controllers\Profile\Modules\ActController::class,'index'])->name('modules.act.index');
                //Route::get('/act/draft', [\App\Http\Controllers\Profile\Modules\ActController::class,'draft'])->name('modules.act.draft');
                Route::get('/act/create', [\App\Http\Controllers\Profile\Modules\ActController::class,'create'])->name('modules.act.create');
                Route::post('/act/store', [\App\Http\Controllers\Profile\Modules\ActController::class,'store'])->name('modules.act.store');
                Route::get('/act/edit/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'edit'])->name('modules.act.edit');
                Route::get('/act/view/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'view'])->name('modules.act.view');
                Route::post('/act/update/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'update'])->name('modules.act.update');
                Route::put('/act/destroy/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'destroy'])->name('modules.act.destroy');
               // Route::post('/act/get-contracts', [\App\Http\Controllers\Profile\Modules\ActController::class,'getContracts'])->name('modules.act.get_contracts');
                Route::post('/act/get-company-info', [\App\Http\Controllers\Profile\Modules\ActController::class,'getCompanyInfo'])->name('modules.act.get_company_info');
                Route::get('/act/exportPdf/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'exportPdf'])->name('modules.act.exportPdf');
                Route::get('/act/print/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'print'])->name('modules.act.print');
                Route::post('/act/sign/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'sign'])->name('modules.act.sign');
                Route::get('/act/download/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'download'])->name('modules.act.download');
                Route::get('/act/check-status/{act}', [\App\Http\Controllers\Profile\Modules\ActController::class,'checkStatus'])->name('modules.act.check-status');

                Route::get('/doc/create', [\App\Http\Controllers\Profile\Modules\DocController::class,'create'])->name('modules.doc.create');
                Route::post('/doc/store', [\App\Http\Controllers\Profile\Modules\DocController::class,'store'])->name('modules.doc.store');
                Route::get('/doc/edit/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'edit'])->name('modules.doc.edit');
                Route::get('/doc/view/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'view'])->name('modules.doc.view');
                Route::post('/doc/update/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'update'])->name('modules.doc.update');
                Route::put('/doc/destroy/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'destroy'])->name('modules.doc.destroy');
                Route::get('/doc/exportPdf/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'exportPdf'])->name('modules.doc.exportPdf');
                Route::get('/doc/print/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'print'])->name('modules.doc.print');
                Route::post('/doc/sign/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'sign'])->name('modules.doc.sign');
                Route::get('/doc/download/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'download'])->name('modules.doc.download');
                Route::get('/doc/check-status/{doc}', [\App\Http\Controllers\Profile\Modules\DocController::class,'checkStatus'])->name('modules.doc.check-status');

                Route::get('/waybill/create', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'create'])->name('modules.waybill.create');
                Route::post('/waybill/store', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'store'])->name('modules.waybill.store');
                Route::get('/waybill/edit/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'edit'])->name('modules.waybill.edit');
                Route::get('/waybill/view/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'view'])->name('modules.waybill.view');
                Route::post('/waybill/update/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'update'])->name('modules.waybill.update');
                Route::put('/waybill/destroy/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'destroy'])->name('modules.waybill.destroy');
                Route::post('/waybill/sign/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'sign'])->name('modules.waybill.sign');
                Route::get('/waybill/print/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'print'])->name('modules.waybill.print');
                Route::get('/waybill/check-status/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'checkStatus'])->name('modules.waybill.check-status');
                Route::get('/waybill/exportPdf/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'exportPdf'])->name('modules.waybill.exportPdf');
                Route::get('/waybill/download/{waybill}', [\App\Http\Controllers\Profile\Modules\WaybillController::class,'download'])->name('modules.waybill.download');

            });

            Route::get('/get-certificate-info', 'StyxController@getCertificateInfo')->name('styx.getCertificateInfo');

        });

    });

    Route::group(['namespace' => 'Admin','middleware'=>['auth','admin']], function () {

        Route::prefix('admin')->group(function () {

            Route::get('/', 'AdminController@index')->name('dashboard');
            Route::get('/index', 'AdminController@index')->name('admin.index');

            Route::group(['prefix'=>'tarif','name'=>'tarif'],function() {
                Route::get('/', 'TarifController@index')->name('admin.tarif.index');
                Route::get('/create', 'TarifController@create')->name('admin.tarif.create');
                Route::post('/store', 'TarifController@store')->name('admin.tarif.store');
                Route::get('/edit/{tarif}', 'TarifController@edit')->name('admin.tarif.edit');
                Route::put('/update/{tarif}', 'TarifController@update')->name('admin.tarif.update');
                Route::put('/destroy/{tarif}', 'TarifController@destroy')->name('admin.tarif.destroy');
            });

            Route::group(['prefix'=>'nomenklature','name'=>'nomenklature'],function() {
                Route::get('/', [\App\Http\Controllers\Admin\NomenklatureController::class, 'index'])->name('admin.nomenklature.index');
                Route::get('/create', [\App\Http\Controllers\Admin\NomenklatureController::class, 'create'])->name('admin.nomenklature.create');
                Route::post('/create', [\App\Http\Controllers\Admin\NomenklatureController::class, 'store'])->name('admin.nomenklature.store');
                Route::get('/edit/{nomenklature}', [\App\Http\Controllers\Admin\NomenklatureController::class, 'edit'])->name('admin.nomenklature.edit');
                Route::put('/update/{nomenklature}', [\App\Http\Controllers\Admin\NomenklatureController::class, 'update'])->name('admin.nomenklature.update');
                Route::put('/destroy/{nomenklature}', [\App\Http\Controllers\Admin\NomenklatureController::class, 'destroy'])->name('admin.nomenklature.destroy');
            });


            Route::group(['prefix'=>'payment','name'=>'payment'],function() {
                Route::get('/', 'PaymentController@index')->name('admin.payment.index');
                //Route::get('/create', 'PaymentController@create')->name('admin.payment.create');
                Route::post('/store', 'PaymentController@store')->name('admin.payment.store');
                Route::get('/edit/{payment}', 'PaymentController@edit')->name('admin.payment.edit');
                Route::put('/update/{payment}', 'PaymentController@update')->name('admin.payment.update');
                Route::put('/destroy/{payment}', 'PaymentController@destroy')->name('admin.payment.destroy');
            });

            Route::group(['prefix'=>'payment_system','name'=>'payment_system'],function() {
                Route::get('/', 'PaymentSystemController@index')->name('admin.payment_system.index');
                Route::get('/create', 'PaymentSystemController@create')->name('admin.payment_system.create');
                Route::post('/store', 'PaymentSystemController@store')->name('admin.payment_system.store');
                Route::get('/edit/{payment_system}', 'PaymentSystemController@edit')->name('admin.payment_system.edit');
                Route::put('/update/{payment_system}', 'PaymentSystemController@update')->name('admin.payment_system.update');
                Route::put('/destroy/{payment_system}', 'PaymentSystemController@destroy')->name('admin.payment_system.destroy');
            });

            Route::group(['prefix'=>'ikpu','name'=>'ikpu'],function() {
                Route::get('/', 'IkpuController@index')->name('admin.ikpu.index');
                Route::get('/create', 'IkpuController@create')->name('admin.ikpu.create');
                Route::post('/store', 'IkpuController@store')->name('admin.ikpu.store');
                Route::get('/edit/{ikpu}', 'IkpuController@edit')->name('admin.ikpu.edit');
                Route::put('/update/{ikpu}', 'IkpuController@update')->name('admin.ikpu.update');
                Route::put('/destroy/{ikpu}', 'IkpuController@destroy')->name('admin.ikpu.destroy');
            });
            Route::group(['prefix'=>'category','name'=>'category'],function() {
                Route::get('/', 'CategoryController@index')->name('admin.category.index');
                Route::get('/create', 'CategoryController@create')->name('admin.category.create');
                Route::post('/store', 'CategoryController@store')->name('admin.category.store');
                Route::get('/edit/{category}', 'CategoryController@edit')->name('admin.category.edit');
                Route::put('/update/{category}', 'CategoryController@update')->name('admin.category.update');
                Route::put('/destroy/{category}', 'CategoryController@destroy')->name('admin.category.destroy');
            });
            Route::group(['prefix'=>'unit','name'=>'unit'],function() {
                Route::get('/', 'UnitController@index')->name('admin.unit.index');
                Route::get('/create', 'UnitController@create')->name('admin.unit.create');
                Route::post('/store', 'UnitController@store')->name('admin.unit.store');
                Route::get('/edit/{unit}', 'UnitController@edit')->name('admin.unit.edit');
                Route::put('/update/{unit}', 'UnitController@update')->name('admin.unit.update');
                Route::put('/destroy/{unit}', 'UnitController@destroy')->name('admin.unit.destroy');
            });
            Route::group(['prefix'=>'package','name'=>'package'],function() {
                Route::get('/', 'PackageController@index')->name('admin.package.index');
                Route::get('/create', 'PackageController@create')->name('admin.package.create');
                Route::post('/store', 'PackageController@store')->name('admin.package.store');
                Route::get('/edit/{package}', 'PackageController@edit')->name('admin.package.edit');
                Route::put('/update/{package}', 'PackageController@update')->name('admin.package.update');
                Route::put('/destroy/{package}', 'PackageController@destroy')->name('admin.package.destroy');
            });

            Route::group(['prefix'=>'nds','name'=>'nds'],function() {
                Route::get('/', 'NdsController@index')->name('admin.nds.index');
                Route::get('/create', 'NdsController@create')->name('admin.nds.create');
                Route::post('/store', 'NdsController@store')->name('admin.nds.store');
                Route::get('/edit/{nds}', 'NdsController@edit')->name('admin.nds.edit');
                Route::put('/update/{nds}', 'NdsController@update')->name('admin.nds.update');
                Route::put('/destroy/{nds}', 'NdsController@destroy')->name('admin.nds.destroy');
            });

            Route::group(['prefix'=>'plan','name'=>'plan'],function() {
                Route::get('/', 'PlanController@index')->name('admin.plan.index');
                Route::get('/create', 'PlanController@create')->name('admin.plan.create');
                Route::post('/store', 'PlanController@store')->name('admin.plan.store');
                Route::get('/edit/{plan}', 'PlanController@edit')->name('admin.plan.edit');
                Route::put('/update/{plan}', 'PlanController@update')->name('admin.plan.update');
                Route::put('/destroy/{plan}', 'PlanController@destroy')->name('admin.plan.destroy');
            });


            Route::group(['prefix'=>'modules'], function () {

                Route::get('/payment_order/', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'index'])->name('admin.modules.payment_order.index');
                Route::get('/payment_order/draft', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'draft'])->name('admin.modules.payment_order.draft');
                Route::get('/payment_order/create', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'create'])->name('admin.modules.payment_order.create');
                Route::post('/payment_order/store', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'store'])->name('admin.modules.payment_order.store');
                Route::get('/payment_order/edit/{payment_order}', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'edit'])->name('admin.modules.payment_order.edit');
                Route::post('/payment_order/update/{payment_order}', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'update'])->name('admin.modules.payment_order.update');
                Route::post('/payment_order/destroy/{payment_order}', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'destroy'])->name('admin.modules.payment_order.destroy');
                Route::any('/payment_order/invoices', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'getInvoices'])->name('admin.modules.payment_order.invoices');
                //Route::any('/payment_order/print', [\App\Http\Controllers\Admin\Modules\PaymentOrderController::class,'print'])->name('admin.modules.payment_order.print');

                Route::get('/contract/', [\App\Http\Controllers\Admin\Modules\ContractController::class,'index'])->name('admin.modules.contract.index');
                Route::get('/contract/draft', [\App\Http\Controllers\Admin\Modules\ContractController::class,'draft'])->name('admin.modules.contract.draft');
                Route::get('/contract/create', [\App\Http\Controllers\Admin\Modules\ContractController::class,'create'])->name('admin.modules.contract.create');
                Route::post('/contract/store', [\App\Http\Controllers\Admin\Modules\ContractController::class,'store'])->name('admin.modules.contract.store');
                Route::get('/contract/edit/{contract}', [\App\Http\Controllers\Admin\Modules\ContractController::class,'edit'])->name('admin.modules.contract.edit');
                Route::post('/contract/update/{contract}', [\App\Http\Controllers\Admin\Modules\ContractController::class,'update'])->name('admin.modules.contract.update');
                Route::post('/contract/destroy/{contract}', [\App\Http\Controllers\Admin\Modules\ContractController::class,'destroy'])->name('admin.modules.contract.destroy');
                Route::post('/contract/invoices', [\App\Http\Controllers\Admin\Modules\ContractController::class,'getInvoices'])->name('admin.modules.contract.invoices');
                //Route::post('/contract/print', [\App\Http\Controllers\Admin\Modules\ContractController::class,'print'])->name('admin.modules.contract.print');

                Route::get('/incoming_order/', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'index'])->name('admin.modules.incoming_order.index');
                Route::get('/incoming_order/draft', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'draft'])->name('admin.modules.incoming_order.draft');
                Route::get('/incoming_order/create', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'create'])->name('admin.modules.incoming_order.create');
                Route::post('/incoming_order/store', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'store'])->name('admin.modules.incoming_order.store');
                Route::get('/incoming_order/edit/{incoming_order}', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'edit'])->name('admin.modules.incoming_order.edit');
                Route::post('/incoming_order/update/{incoming_order}', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'update'])->name('admin.modules.incoming_order.update');
                Route::post('/incoming_order/destroy/{incoming_order}', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'destroy'])->name('admin.modules.incoming_order.destroy');
                //Route::post('/incoming_order/print', [\App\Http\Controllers\Admin\Modules\IncomingOrderController::class,'print'])->name('admin.modules.incoming_order.print');

                Route::get('/expense_order/', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'index'])->name('admin.modules.expense_order.index');
                Route::get('/expense_order/draft', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'draft'])->name('admin.modules.expense_order.draft');
                Route::get('/expense_order/create', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'create'])->name('admin.modules.expense_order.create');
                Route::post('/expense_order/store', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'store'])->name('admin.modules.expense_order.store');
                Route::get('/expense_order/edit/{expense_order}', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'edit'])->name('admin.modules.expense_order.edit');
                Route::post('/expense_order/update/{expense_order}', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'update'])->name('admin.modules.expense_order.update');
                Route::post('/expense_order/destroy/{expense_order}', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'destroy'])->name('admin.modules.expense_order.destroy');
                Route::any('/expense_order/cases', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'getCases'])->name('admin.modules.expense_order.cases');
                //Route::any('/expense_order/print', [\App\Http\Controllers\Admin\Modules\ExpenseOrderController::class,'print'])->name('admin.modules.expense_order.print');

                Route::get('/product/', [\App\Http\Controllers\Admin\Modules\ProductController::class,'index'])->name('admin.modules.product.index');
                Route::get('/product/draft', [\App\Http\Controllers\Admin\Modules\ProductController::class,'draft'])->name('admin.modules.product.draft');
                Route::get('/product/create', [\App\Http\Controllers\Admin\Modules\ProductController::class,'create'])->name('admin.modules.product.create');
                Route::post('/product/store', [\App\Http\Controllers\Admin\Modules\ProductController::class,'store'])->name('admin.modules.product.store');
                Route::get('/product/edit/{product}', [\App\Http\Controllers\Admin\Modules\ProductController::class,'edit'])->name('admin.modules.product.edit');
                Route::post('/product/update/{product}', [\App\Http\Controllers\Admin\Modules\ProductController::class,'update'])->name('admin.modules.product.update');
                Route::post('/product/destroy/{product}', [\App\Http\Controllers\Admin\Modules\ProductController::class,'destroy'])->name('admin.modules.product.destroy');
                Route::any('/product/nomenklatures', [\App\Http\Controllers\Admin\Modules\ProductController::class,'getNomenklatures'])->name('admin.modules.product.nomenklatures');
                Route::any('/product/remains', [\App\Http\Controllers\Admin\Modules\ProductController::class,'remains'])->name('admin.modules.product.remains');
                Route::any('/product/receipts', [\App\Http\Controllers\Admin\Modules\ProductController::class,'receipts'])->name('admin.modules.product.receipts');
                Route::any('/product/sales', [\App\Http\Controllers\Admin\Modules\ProductController::class,'sales'])->name('admin.modules.product.sales');
                //Route::any('/product/print', [\App\Http\Controllers\Admin\Modules\ProductController::class,'print'])->name('admin.modules.product.print');


                Route::get('/guarant/', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'index'])->name('admin.modules.guarant.index');
                Route::get('/guarant/draft', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'draft'])->name('admin.modules.guarant.draft');
                Route::get('/guarant/create', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'create'])->name('admin.modules.guarant.create');
                Route::post('/guarant/store', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'store'])->name('admin.modules.guarant.store');
                Route::get('/guarant/edit/{guarant}', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'edit'])->name('admin.modules.guarant.edit');
                Route::post('/guarant/update/{guarant}', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'update'])->name('admin.modules.guarant.update');
                Route::post('/guarant/destroy/{guarant}', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'destroy'])->name('admin.modules.guarant.destroy');
                //Route::post('/guarant/print', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'print'])->name('admin.modules.guarant.print');
               // Route::post('/guarant/invoices', [\App\Http\Controllers\Admin\Modules\GuarantController::class,'getInvoices'])->name('admin.modules.guarant.invoices');

                Route::get('/company_account/', [\App\Http\Controllers\Admin\CompanyAccountController::class,'index'])->name('admin.company_account.index');
                Route::get('/company_account/accounts/{company}', [\App\Http\Controllers\Admin\CompanyAccountController::class,'accounts'])->name('admin.company_account.accounts');
                Route::get('/company_account/create', [\App\Http\Controllers\Admin\CompanyAccountController::class,'create'])->name('admin.company_account.create');
                Route::post('/company_account/store', [\App\Http\Controllers\Admin\CompanyAccountController::class,'store'])->name('admin.company_account.store');
                Route::get('/company_account/edit/{company_account}', [\App\Http\Controllers\Admin\CompanyAccountController::class,'edit'])->name('admin.company_account.edit');
                Route::post('/company_account/update/{company_account}', [\App\Http\Controllers\Admin\CompanyAccountController::class,'update'])->name('admin.company_account.update');
                //Route::post('/company_account/destroy/{company_account}', [\App\Http\Controllers\Admin\CompanyAccountController::class,'destroy'])->name('admin.company_account.destroy');
                //Route::any('/expense_order/print', [\App\Http\Controllers\Admin\CompanyAccountController::class,'print'])->name('admin.expense_order.print');


            });

            Route::group(['prefix'=>'news','name'=>'news'],function() {
                Route::get('/', 'NewsController@index')->name('admin.news.index');
                Route::get('/create', 'NewsController@create')->name('admin.news.create');
                Route::post('/store', 'NewsController@store')->name('admin.news.store');
                Route::get('/edit/{news}', 'NewsController@edit')->name('admin.news.edit');
                Route::put('/update/{news}', 'NewsController@update')->name('admin.news.update');
                Route::put('/destroy/{news}', 'NewsController@destroy')->name('admin.news.destroy');
            });

            Route::group(['prefix'=>'user','name'=>'user'],function() {
                Route::get('/', 'UserController@index')->name('admin.user.index');
                Route::get('/create', 'UserController@create')->name('admin.user.create');
                Route::post('/store', 'UserController@store')->name('admin.user.store');
                Route::get('/edit/{user}', 'UserController@edit')->name('admin.user.edit');
                Route::put('/update/{user}', 'UserController@update')->name('admin.user.update');
                Route::put('/destroy/{user}', 'UserController@destroy')->name('admin.user.destroy');
                Route::get('/export/{role}', 'UserController@export')->name('admin.user.export');

            });

            Route::group(['prefix'=>'company','name'=>'company'],function() {
                Route::get('/', 'CompanyController@index')->name('admin.company.index');
                Route::get('/create', 'CompanyController@create')->name('admin.company.create');
                Route::post('/store', 'CompanyController@store')->name('admin.company.store');
                Route::get('/edit/{company}', 'CompanyController@edit')->name('admin.company.edit');
                Route::put('/update/{company}', 'CompanyController@update')->name('admin.company.update');
                Route::put('/destroy/{company}', 'CompanyController@destroy')->name('admin.company.destroy');

            });

            Route::group(['prefix'=>'role','name'=>'role'],function() {
                Route::get('/', 'RoleController@index')->name('admin.role.index');
                Route::get('/create', 'RoleController@create')->name('admin.role.create');
                Route::post('/store', 'RoleController@store')->name('admin.role.store');
                Route::get('/edit/{role}', 'RoleController@edit')->name('admin.role.edit');
                Route::put('/update/{role}', 'RoleController@update')->name('admin.role.update');
                Route::put('/destroy/{role}', 'RoleController@destroy')->name('admin.role.destroy');
            });

			Route::group(['prefix'=>'city','name'=>'city'],function() {
				Route::get('/', 'CityController@index')->name('admin.city.index');
				Route::get('/create', 'CityController@create')->name('admin.city.create');
				Route::post('/store', 'CityController@store')->name('admin.city.store');
				Route::get('/edit/{city}', 'CityController@edit')->name('admin.city.edit');
				Route::put('/update/{city}', 'CityController@update')->name('admin.city.update');
				Route::put('/destroy/{city}', 'CityController@destroy')->name('admin.city.destroy');
				Route::post('/get-cities', 'CityController@getCities')->name('admin.city.get-cities');
			});

			Route::group(['prefix'=>'region','name'=>'region'],function() {
				Route::get('/', 'RegionController@index')->name('admin.region.index');
				Route::get('/create', 'RegionController@create')->name('admin.region.create');
				Route::post('/store', 'RegionController@store')->name('admin.region.store');
				Route::get('/edit/{region}', 'RegionController@edit')->name('admin.region.edit');
				Route::put('/update/{region}', 'RegionController@update')->name('admin.region.update');
				Route::put('/destroy/{region}', 'RegionController@destroy')->name('admin.region.destroy');
			});


            Route::group(['prefix'=>'district','name'=>'district'],function() {
                Route::get('/', 'DistrictController@index')->name('admin.district.index');
                Route::get('/create', 'DistrictController@create')->name('admin.district.create');
                Route::post('/store', 'DistrictController@store')->name('admin.district.store');
                Route::get('/edit/{district}', 'DistrictController@edit')->name('admin.district.edit');
                Route::put('/update/{district}', 'DistrictController@update')->name('admin.district.update');
                Route::put('/destroy/{district}', 'DistrictController@destroy')->name('admin.district.destroy');
                Route::post('/get-districts', 'DistrictController@getDistricts')->name('admin.district.get-districts');
            });


            Route::post('image/destroy/{id}','ImageController@destroy');

        });

    });

});

//Auth::routes();
