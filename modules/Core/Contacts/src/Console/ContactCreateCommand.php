<?php

namespace Modules\Core\Contacts\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Contacts\Http\Requests\StoreContactRequest;
use Modules\Core\Contacts\Models\Contact;

class ContactCreateCommand extends Command
{
    /**
     * You can accept tenant_id as an argument or option.
     * Example usage: php artisan contact:create 1
     */
    protected $signature = 'contact:create {tenant_id?}';
    protected $description = 'Create a new contact for a given tenant';

    public function handle(): void
    {
        $this->info('Creating a new contact...');

        // Resolve tenant_id from argument or prompt the user
        $tenantId = $this->argument('tenant_id');
        if (is_null($tenantId)) {
            $tenantId = $this->ask('Please enter the Tenant ID');
        }

        // Gather remaining contact data
        $data['tenant_id'] = $tenantId;
        $data['name']      = $this->ask('Name');
        $data['email']     = $this->ask('Email address');
        $data['phone']     = $this->ask('Phone in E.164 format (must be +61 or +64)');

        // Validate the data using the same rules as our API
        $request = new StoreContactRequest();
        $request->merge($data);
        $validator = Validator::make($data, $request->rules(), $request->messages());

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line(" - $error");
            }
            return;
        }

        // Create the new contact record
        Contact::create($data);

        $this->info('Contact created successfully!');
    }
}
