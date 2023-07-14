<?php

namespace App\Constants;

class Constants
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
	public const ROLE_CLIENT_SERVICE_CUSTOMER = 'ROLE_CLIENT_SERVICE_CUSTOMER';
	public const ROLE_CLIENT_SERVICE_PROVIDER = 'ROLE_CLIENT_SERVICE_PROVIDER';
	public const ROLE_EDITOR = 'ROLE_EDITOR';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
	
	public const USER_STATUS_ACTIVE = 'active';
	public const USER_STATUS_INACTIVE = 'inactive';
	public const USER_STATUS_DISABLED = 'disabled';
	public const USER_STATUS_ENABLED = 'enabled';
	
	public const LEGAL_CASE_STATUS_SUBMITTED = 'submitted';
	public const LEGAL_CASE_STATUS_CATEGORIZED = 'categorized';
    public const LEGAL_CASE_STATUS_IN_PROGRESS = 'inProgress';
	public const LEGAL_CASE_STATUS_INCOMPLETE = 'incomplete';
	public const LEGAL_CASE_STATUS_REJECTED = 'rejected';
    public const LEGAL_CASE_STATUS_ASSIGNED = 'assigned';
    public const LEGAL_CASE_STATUS_COMPLETED = 'completed';
	
	public const LEGAL_CASE_CATEGORY_LEGAL_ADVICE = 'Conseil juridique';
	public const LEGAL_CASE_CATEGORY_AUDIT = 'Audit';
	public const LEGAL_CASE_CATEGORY_EXPERTISE = 'Expertise';
	public const LEGAL_CASE_CATEGORY_DRAFTING_OF_LEGAL_DOCUMENTS = 'Rédaction des actes juridique';
	public const LEGAL_CASE_CATEGORY_REAL_STATE_TRANSACTIONS = 'Transactions immobilières';
	public const LEGAL_CASE_CATEGORY_BUSINESS_CREATION = 'Créations des entreprises';
	public const LEGAL_CASE_CATEGORY_LEGAL_SECRETARIAT = 'Secrétariat juridiques';
	
	public const USER_QUESTION_STATUS_ANSWERED = 'answered';
    public const USER_QUESTION_STATUS_UNANSWERED = 'unanswered';
	
}