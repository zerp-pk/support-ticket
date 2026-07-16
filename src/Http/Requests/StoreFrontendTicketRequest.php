<?php

namespace Zerp\SupportTicket\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreFrontendTicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * The company whose help centre this is, taken from the slug in the URL.
     *
     * This form is public, so the submitter is usually a guest and creatorId() means
     * nothing here - the category must belong to the portal's company, not to whoever
     * happens to be logged in. Returns 0 for an unknown slug, which fails the rule.
     */
    private function portalCompanyId(): int
    {
        return (int) User::where('slug', $this->route('slug'))->value('id');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'category' => 'required|exists:ticket_categories,id,created_by,' . $this->portalCompanyId(),
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:In Progress,On Hold,Closed',
            'account_type' => 'required|string|in:custom',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'sometimes|file|max:10240'
        ];
    }
}