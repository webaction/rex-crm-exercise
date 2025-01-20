# REX Software CRM Exercise
Coding exercise - CRM that will over time expand to cover a broad domain.

**Context:**

We have teams of 4-5 engineers working per repo.
Our business logic tends to need to be used synchronously, asynchronously + via multiple interfaces (cli vs http).
Our apps are heavily CRUD based and often multiple resources may need to be manipulated within a single database transaction in the context of a single user request.
Testing of business logic and rules around that is important
Modules should be structured as consistently as possible.
Error messages should be as informative as possible to API consumers who may be third parties.
Data sets can grow quite large.
This is a multi-tenant setup.
Given the size of the code base, documentation is important.

**Problem & Acceptance Criteria:**

You're building a CRM that will over time expand to cover a broad domain. Assume you're preparing production grade code that takes the above into account.

Using laravel, provide a module that can be accessed via API + CLI.
The module should allow for the storage and retrieval of contacts (names, phones and emails).
Phone numbers and emails stored must be valid emails.
Phones should be stored in E164 format.
Phone numbers can only be Australian or New Zealand phone numbers.
Contacts should be able to be efficiently retrieved based on their phone number or email domain.
Expose a method that allows for a contact to be called (doesn't have to actually place the call but should consider possible outcomes based on making a call to third party service).
Please include a short readme that explains the reasons for your chosen code structure / architecture that relates back to the above.”


# Project Architecture & Code Structure

I have divide the codebase into **four primary modules**:
1. **Core** - Houses fundamental CRM submodules and shared functionality (Contacts, Accounts, Agents, Properties, etc.).
2. **Integrations** – Manages third-party services (Twilio, Slack, Xero, Zapier)
3. **Marketing** - Covers lead generation, campaigns, email marketing, and social media.
4. **Sales** - Handles listings, inspections, appraisals, transactions

Within each module, we would maintain the standard Laravel coding conventions around naming, directory structure (Http, Models, etc.)

## Why This Structure?

### 1. Domain-Driven Organisation
Grouping code by **domain feature** (Core, Integrations, Marketing, Sales) aligns business logic with the structure of our application.
This makes it easier to:
- Clearly identify where specific features live (e.g. "Listings" in Sales).
- Avoid overlapping responsibilities or code duplication between modules.

### 2. Scalable Team Setup
The teams are split into **feature squads** - each with 4 - 5 developers.
By dedicating modules to specific domains:
- Each team can focus on a consistent set of features.
- Merge conflicts and coordination overhead are reduced.
- Teams can work in parallel without stepping on each other's toes.
- You can use CODEOWNERS file to define individuals or teams that are responsible for modules

### 3. Maintainability & Reusability
Each module encapsulates its own controllers, models, routes, and views, making it:
- Easier to **replace** or **upgrade** parts of system independently.
- Simpler to **test** in isolation. Each module can have focused unit and integrations test.
- Straightforward to apply **cross-cutting changes** (e.g. logging, permissions) within a single module.

### 4. Clear Boundaries & Responsibilities
A **Core** module handles shared entities (like Contacts and Properties), while specialised modules (e.g. Sales for listings and transactions) rely on those common models.
Likewise, all third-party integrations live in one place, making them **centralised** and easy to manage or extend.
