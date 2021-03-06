<?php

namespace App\Http\Controllers;

use App\Company;
use App\Customer;
use App\Events\NewCustomerHasRegisteredEvent;
use App\Mail\WelcomeNewUserMail;
// use App\Http\Controllers\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
// use intervention\Image\Facades\Image;



class CustomersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('auth')->except(['index']);  ->This method is for preventing certain actions like editing and adding customers to the list, even though it gives access to the customer list page.
    }
    //  public function list(). This is replaced with the function below
     public function index() {
        // $customers = [
        //                 'John Doe',
        //                 'Jane Doe',
        //                 'Bob the Builder',
        //                 'Another Name',
        //             ];
        // the code above will be replaced with the one below, as we are now fetching from DB.

            $customers = Customer::with('company')->paginate(15);

        // where('active', 1)->get();

        // where('active', 0)->get();

        // dd($inactiveCustomers);
        // dd($customers);
        // the codes above is to help display the db on the browser

                    // return view('internals.customers', [
                    //     // 'customers' => $customers,

                    //     'activeCustomers' => $activeCustomers,
                    //     'inactiveCustomers' => $inactiveCustomers,
                    // ]);

                    // the code above can be re-written with the shorthand below


                    return view('customers.index', compact('customers'));
     }

        public function create() {

        $companies = Company::all();
        $customer = new Customer();
        return view('/customers.create',
        compact('companies', 'customer'));



        // incase we want to paginate, we use the code below instead.
        // $companies= Company::paginate(2);

        // return view('customers.create', compact('companies'));

     }

     public function store() {

        $this->authorize('create', Customer::class);

        // to validate an entry in a form below

         //$data =  $customer = new Customer();
        // $customer->name = request('name');
        // $customer->email = request('email');
        // $customer->active = request('active');
        // $customer->save();


        // instead of having the repititon of what's above below, we can use a code to erase the repition. $customer = Customer::create($data); and after that, we can get rid of the code below

       $customer = Customer::create($this->validateRequest());

    //    $this->storeImage($customer);

       event(new NewCustomerHasRegisteredEvent($customer));


        return redirect('customers');

        //  dd(request('name'));

     }

     public function show(Customer $customer) {

            // $customer = Customer::find($customer);
            //  to fix the error coming from entering 30 check below
            // $customer = Customer::where('id', $customer)->firstOrFail();

            return view('customers.show', compact('customer'));
     }

     public function edit(Customer $customer) {

        $companies = Company::all();

        return view('customers.edit', compact('customer', 'companies'));
     }


     public function update(Customer $customer) {

        $customer->update($this->validateRequest());

        return redirect('customers/' . $customer->id);
     }


     public function destroy(Customer $customer) {

        $this->authorize('delete', $customer);
        
        $customer->delete();

        return redirect('customers');
     }

     private function validateRequest() {

        // we can turn all the validation into a tap which allows us run a closure.

            return tap(request()->validate([

                'name' => 'required|min:3',
                'email' => 'required|email',
                'active' => 'required',
                'company_id' => 'required',

                // 'image' => 'required|image',this is for requiring images. we will replace return request() with $validatedData

            ]), function() {

                if (request()->hasFile('image')) {
                    // dd(request()->image);
                    request()->validate([
                        'image' => 'file|image|max:5000',
                    ]);
                }
            });

        }

        // private function storeImage($customer) {

        //     if(request()->has('image')) {
        //         $customer->update([
        //             'image' => request()->image->store('uploads', 'public'),
        //         ]);


        //         $image = Image::make(public_path('storage/' . $customer->image))->crop(300, 300);
        //         $image->save();


            // $image = Image::make(public_path('storage/' . $customer->image))->fit(300, 300, null, 'top-left');
            // $image->save();
        // }

    // }



// Threw an error from this page while working on mail ->>>"Class 'App\Http\Controllers\NewCustomerHasRegisteredEvent' not found"
// Solution->> This means that we forgot to import a class at the top. In this case ->>>> use App\Events\NewCustomerHasRegisteredEvent;
// N/B: Whenever you see such notifications where a class is missing, it means you forgot to import a class.
}
