<?php
include ("lib/main.php");
if ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest') {
    $data = json_decode($_POST['data']);
    $request = $data->method;
    dd('mmmmmmmm');
/*
 * creating Request
 */
    if($request==='create_request'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createRequests(
                $data->name
            )
        )
        );
    }
    if($request==='create_member'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createMember(
                $data->member_status,
                $data->member_church,
                $data->member_gender,
                $data->member_name,
                $data->member_phone,
                $data->member_ministry
            )
        )
        );
    }
    if($request==='edit_request'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editRequests(
                $data->name,
                $data->id
            )
        )
        );
    }
    if($request==='comment_request'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->commentRequests(
                $data->comment,
                $data->id
            )
        )
        );
    }
    /* Ausman
    * Create a Department
    * 11/12/2020
    */
    if($request==='create_division'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createDivision(
                $data->address,
                $data->name
            )
        )
        );
    }
    if($request==='create_bank_account_allocation'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createBankAllocation(
                $data->bank_name,
                $data->account_name
            )
        )
        );
    }
    if($request==='edit_bank_details'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editBankDetails(
                $data->number,
                $data->bank_name,
                $data->center,
                $data->acc_name,
                $data->id
            )
        )
        );
    }
    if($request==='transfer_amount'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->transferAmount(
                $data->money_password,
                $data->money_account,
                $data->money_amount,
                $data->money_type,
                $data->money_bank,
                $data->month
            )
        )
        );
    }
    if($request==='create_asset'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createAsset(
                $data->name,
                $data->serial,
                $data->category,
                $data->cost,
                $data->life,
                $data->date
            )
        )
        );
    }
    if($request==='edit_asset'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editAsset(
                $data->name,
                $data->serial,
                $data->category,
                $data->cost,
                $data->life,
                $data->id,
                $data->date
            )
        )
        );
    }
    if($request==='pastor_amount'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->addPastorAmount(
                $data->pastor,
                $data->tt_amount
            )
        )
        );
    }
    if($request==='delete_position'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deletePosition(
                $data->division_id
            )
        )
        );
    }
    if($request==='create_admin_transactions'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->adminTransactions(
                $data->bank_account,
                $data->pastor,
                $data->type,
                $data->account_id,
                $data->desc,
                $data->t_date,
                $data->amount,
                $data->bank_id
            )
        )
        );
    }
    if($request==='edit_executive'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editExecutive(
                $data->amount,
                $data->date,
                $data->id
            )
        )
        );
    }
    if($request==='edit_position'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editPostion(
                $data->id,
                $data->name
            )
        )
        );
    }
    if($request==='create_position'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createPostion(
                $data->name
            )
        )
        );
    }
    if($request==='edit_payment_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editPayment(
                $data->money_amount,
                $data->money_id,
                $data->bank_id,
                $data->new_amount
            )
        )
        );
    }
    if($request==='create_admin_transaction_dr'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->adminTransactionDr(
                $data->bank_id,
                $data->account_id,
                $data->desc,
                $data->t_date,
                $data->amount,
                $data->name,
                $data->reference
            )
        )
        );
    }
    if($request==='create_admin_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->adminTransaction(
                $data->pastor,
                $data->type,
                $data->account_id,
                $data->desc,
                $data->t_date,
                $data->amount,
                $data->received
            )
        )
        );
    }
    if($request==='edit_section_name'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editSectionName(
                $data->id,
                $data->section_name
            )
        )
        );
    }
    if($request==='create_user_types'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createUserType(
                $data->user_role,
                $data->user_level,
                $data->user_privilege
            )
        )
        );
    }
    if($request==='create_users'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createUsers(
                $data->user_name,
                $data->user_password,
                $data->user_role,
                $data->user_level,
                $data->user_church,
                $data->user_section,
                $data->user_district,
                $data->user_division
            )
        )
        );
    }
//    if($request==='create_member'){
//        include("controllers/human_resource.php");
//        $faculty = new human_resource();
//        exit(
//        json_encode(
//            $faculty->createMember(
//                $data->member_dob,
//                $data->name,
//                $data->member_gender,
//                $data->member_cell,
//                $data->academic_level,
//                $data->occupations,
//                $data->m_ministry,
//                $data->marital_status,
//                $data->m_home
//            )
//        )
//        );
//    }

    if($request==='delete_division_a'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteAccountD(
                $data->division_id
            )
        )
        );
    }
    if($request==='delete_user_type'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteUserType(
                $data->division_id
            )
        )
        );
    }
    if($request==='edit_section_district'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editDistrictSection(
                $data->id,
                $data->new_district
            )
        )
        );
    }
    if($request==='create_missionary'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createMissionary(
                $data->p_name,
                $data->p_gender,
                $data->phone_number,
                $data->natinality,
                $data->p_status
            )
        )
        );
    }
    if($request==='create_pastor_in'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createPastorIn(
                $data->p_district,
                $data->p_section,
                $data->p_name,
                $data->p_gender,
                $data->p_church,
                $data->p_status,
                $data->m_position
            )
        )
        );
    }
    if($request==='confirm_transfer_amount'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->confirmTransferAmount(
                $data->money_password,
                $data->money_id,
                $data->account_id,
                $data->division,
                $data->money_amount
            )
        )
        );
    }
    if($request==='delete_dt'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteDT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_division'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteDivision(
                $data->division_id
            )
        )
        );
    }
    if($request==='restore_d_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->restoreTTs(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='restore_d_transactions'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->restoreTTsT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_d_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteDDT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_d_transactions'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteDDTs(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='restore_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->restoreT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='restore_p_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->restoreTT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_p_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deletePT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_c_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteCT(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_district'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteDistrict(
                $data->faculty_id
            )
        )
        );
    }

    if($request==='delete_section'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteSection(
                $data->section_id
            )
        )
        );
    }

    if($request==='delete_church'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteChurch(
                $data->church_id
            )
        )
        );
    }

    if($request==='deactivate_church'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deactivateChurch(
                $data->church_id
            )
        )
        );
    }

    if($request==='create_church'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createChurch(
                $data->women,
                $data->men,
                $data->youth,
                $data->children,
                $data->section,
                $data->name
            )
        )
        );
    }
    if($request==='position'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editNecPosition(
                $data->position,
                $data->id
            )
        )
        );
    }
    if($request==='create_pastor'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createPastor(
                $data->p_district,
                $data->p_section,
                $data->name,
                $data->dob,
                $data->gender,
                $data->position,
                $data->church,
                $data->theology,
                $data->academical,
                $data->status,
                $data->home,
                $data->first_credential,
                $data->second_credential,
                $data->third_credential,
                $data->phone_number,
                $data->email,
                $data->spouse_number,
                $data->spouse_home_district,
                $data->spouse_name,
                $data->number_of_children,
                $data->home_district,
                $data->i_position
            )
        )
        );
    }

    if($request==='edit_division'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editDivision(
                $data->id,
                $data->name,
                $data->address,
                $data->director
            )
        )
        );
    }

    if($request==='edit_section'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editSection(
                $data->name,
                $data->director
            )
        )
        );
    }

    if($request==='edit_district'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editDistrict(
                $data->name,
                $data->director
            )
        )
        );
    }
    if($request==='reset_password'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->resetPassword(
                $data->position_id
            )
        )
        );
    }

    if($request==='reverse_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->reverseTransaction(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='reverse_p_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->reverseTransactionMember(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='reverse_district_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->reverseTransactionDistrict(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='reverse_c_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->reverseTransactionChurch(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='church_update'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->updateChurch(
                $data->name,
                $data->director
            )
        )
        );
    }

    if($request==='edit_pastor_details'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->updatePastorDetails(
                $data->p_name,
                $data->home_village,
                $data->phone,
                $data->id,
                $data->p_sex,
                $data->dob,
                $data->cre_1,
                $data->cre_2,
                $data->cre_3,
                $data->children
            )
        )
        );
    }

    if($request==='create_financial'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createFinancial(
                $data->start_at,
                $data->end_at
            )
        )
        );
    }
    if($request==='create_month'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createMonth(
                $data->name,
                $data->start_at,
                $data->end_at
            )
        )
        );
    }
    if($request==='edit_month'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editMonth(
                $data->name,
                $data->id,
                $data->start_at,
                $data->end_at
            )
        )
        );
    }
    if($request==='create_account'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createAccount(
                $data->name,
                $data->type
            )
        )
        );
    }
    if($request==='create_benefit'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createBenefit(
                $data->amount,
                $data->officer,
                $data->t_date
            )
        )
        );
    }

    if($request==='edit_account'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editAccount(
                $data->id,
                $data->name,
                $data->code,
                $data->type
            )
        )
        );
    }

    if($request==='create_budget'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createBudget(
                $data->amount,
                $data->accounts
            )
        )
        );
    }
    if($request==='create_allocated'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createAllocate(
                $data->name,
                $data->type
            )
        )
        );
    }
    if($request==='edit_allocated'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editAllocate(
                $data->allocate_id,
                $data->allocation
            )
        )
        );
    }
    if($request==='edit_allocated'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editAllocate(
                $data->allocate_id,
                $data->allocation
            )
        )
        );
    }
    if($request==='edit_budget'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editBudget(
                $data->amount,
                $data->id
            )
        )
        );
    }

    if($request==='create_bank_account'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createBankAccount(
                $data->bank_name,
                $data->account_number,
                $data->service_center,
                $data->account_name,
                $data->division
            )
        )
        );
    }

    if($request==='edit_bank_account'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editBankAccount(
                $data->bank_name,
                $data->account_number,
                $data->service_center,
                $data->id,
                $data->account_name
            )
        )
        );
    }
    if($request==='edit_amount_district'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editAmountDistrict(
                $data->id,
                $data->amount
            )
        )
        );
    }
    if($request==='edit_amount_general'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editGeneral(
                $data->reason,
                $data->password,
                $data->id
            )
        )
        );
    }
    if($request==='create_event'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createEvent(
                $data->account_name,
                $data->e_date
            )
        )
        );
    }
    if($request==='edit_event'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editEvent(
                $data->id,
                $data->e_date
            )
        )
        );
    }
    if($request==='delete_event'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteEvent(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='delete_accounts'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteAccounts(
                $data->division_id
            )
        )
        );
    }
    if($request==='edit_accounts'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editAccount(
                $data->name,
                $data->id,
                $data->type
            )
        )
        );
    }
    if($request==='delete_ac_allocation'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteAllocatedBanks(
                $data->division_id
            )
        )
        );
    }
    if($request==='create_bank_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createBankTransaction(
                $data->member_1,
                $data->section_1,
                $data->current,
                $data->district,
                $data->pastor,
                $data->church,
                $data->type,
                $data->desc,
                $data->amount,
                $data->ddf,
                $data->account_id,
                $data->agreds_amount,
                $data->general_amount,
                $data->pvt_amount,
                $data->sunday_amount,
                $data->others_amount,
                $data->reference_number,
                $data->amount_others
            )
        )
        );
    }
    if($request==='vacate_church'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->vacateChurch(
                $data->church
            )
        )
        );
    }
    if($request==='generate_receipt'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->generateReceipt(
                $data->general_amount,
                $data->sunday_amount,
                $data->agreds_amount,
                $data->other_amount,
                $data->id,
                $data->reference,
                $data->faculty_id
            )
        )
        );
    }
    if($request==='generate_transactionP'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->generateReceiptP(
                $data->pastor,
                $data->reference,
                $data->amount,
                $data->current,
                $data->account,
                $data->date
            )
        )
        );
    }
    if($request==='create_division_allocations'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->crateDivisionAllocation(
                $data->division,
                $data->account_id
            )
        )
        );
    }
    if($request==='edit_transactionP'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editPT(
                $data->amount,
                $data->name,
                $data->t_date,
                $data->t_type,
                $data->id,
                $data->t_pastor
            )
        )
        );
    }
    if($request==='edit_transactionC'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editCT(
                $data->amount,
                $data->name,
                $data->t_date,
                $data->t_type,
                $data->id
            )
        )
        );
    }

    if($request==='create_bank_transaction_dr'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->drTransaction(
                $data->account_id,
                $data->desc,
                $data->t_date,
                $data->amount,
                $data->name
            )
        )
        );
    }
    if($request==='edit_transactionDr'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editDr(
                $data->amount,
                $data->t_date,
                $data->t_type,
                $data->t_account,
                $data->id
            )
        )
        );
    }
    if($request==='edit_transactionA'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editAT(
                $data->amount,
                $data->t_date,
                $data->t_type,
                $data->t_account,
                $data->id,
                $data->t_church
            )
        )
        );
    }

    if($request==='edit_year'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editFinancial(
                $data->id,
                $data->start_at,
                $data->end_at,
                $data->status
            )
        )
        );
    }

    if($request==='change_section'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changeSection(
                $data->church_id,
                $data->section
            )
        )
        );
    }

    if($request==='add_pastor_child'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->addChildren(
                $data->firstname,
                $data->dob,
                $data->surname,
                $data->gender,
                $data->pastor,
                $data->education
            )
        )
        );
    }
    if($request==='change_child_status'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->childStatus(
                $data->status,
                $data->id
            )
        )
        );
    }
    if($request==='change_child_education'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->childEducation(
                $data->status,
                $data->id
            )
        )
        );
    }
    if($request==='update_pastor_child'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->updateChild(
                $data->firstname,
                $data->dob,
                $data->surname,
                $data->gender,
                $data->id
            )
        )
        );
    }
    elseif($data->method==="add_member_spouse")
    {
        include('controllers/human_resource.php');
        $object = new human_resource();
        $contents = $object::addSpouse(
            $data->firstname,
            $data->dob,
            $data->pastor,
            $data->surname,
            $data->gender,
            $data->education
        );
        exit(
        json_encode(
            $contents
        )
        );
    }
    /* Ausman
* create_position
* 11/12/2020
*/
    if($request==='change_statuss'){
        include ("controllers/human_resource.php");
        $patient = new human_resource();
        exit(
        json_encode(
            $patient->changeStatuss(
                $data->employee_id
            )
        )
        );
    }
    if($request==='change_status'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changeStatus(
                $data->status,
                $data->id
            )
        )
        );
    }
    if($request==='change_spouse_status'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changeSpouseStatus(
                $data->status,
                $data->id
            )
        )
        );
    }
    if($request==='change_spouse_education'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changeSpouseEducation(
                $data->status,
                $data->id
            )
        )
        );
    }
    if($request==='edit_pastor_spouse'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changeSpouseDetails(
                $data->s_district,
                $data->s_phone,
                $data->s_name,
                $data->id
            )
        )
        );
    }

    if($request==='submit_pastor_status'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changePastorStatus(
                $data->pastor_status,
                $data->id
            )
        )
        );
    }

    if($request==='marital_pastor_status'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changePastorMarital(
                $data->pastor_status,
                $data->id
            )
        )
        );
    }

    if($request==='marital_pastor_academic'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changePastorAcademic(
                $data->pastor_status,
                $data->id
            )
        )
        );
    }
    if($request==='login'){
        include("controllers/security.php");
        $faculty = new security();
        exit(
        json_encode(
            $faculty->authenticate(
                $data->userName,
                $data->password
            )
        )
        );
    }
    if($request==='marital_pastor_theology'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changePastorTheology(
                $data->pastor_status,
                $data->id
            )
        )
        );
    }

    if($request==='marital_pastor_next_church'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changePastorChurch(
                $data->pastor_status,
                $data->church_id,
                $data->start_date,
                $data->id,
                $data->position
            )
        )
        );
    }

    if($request==='change_church_details'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changeChurch(
                $data->church_id,
                $data->church_name
            )
        )
        );
    }
    if($request==='edit_home_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editHomeAttendance(
                $data->id,
                $data->_date,
                $data->attendance
            )
        )
        );
    }
    /*
           * Paratech Systems
           * Chaning Password
           * 18/10/2020
           */
    if($request==='change_password'){
        include ("controllers/users.php");
        $teachers = new users();
        exit(
        json_encode(
            $teachers->changePassword(
                $data->password,
                $data->teacher_id
            )
        )
        );
    }
    if($request==='edit_monthly_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editMonthAttendance(
                $data->air,
                $data->holly,
                $data->children,
                $data->youth,
                $data->women,
                $data->men,
                $data->visitor,
                $data->new_people,
                $data->baptized,
                $data->id,
                $data->date
            )
        )
        );
    }
    if($request==='edit_new_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editSundayAttendance(
                $data->id,
                $data->date_attendance,
                $data->attendance
            )
        )
        );
    }
    if($request==='delete_pastor'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deletePastor(
                $data->position_id
            )
        )
        );
    }
    if($request==='delete_users'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteUsers(
                $data->division_id
            )
        )
        );
    }
    if($request==='change_user_name'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->changeUserName(
                $data->user_name,
                $data->id
            )
        )
        );
    }
    if($request==='check_reverse_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->pastorReverse(
                $data->faculty_id,
                $data->reason,
                $data->money_passwords,
                $data->amount
            )
        )
        );
    }
    if($request==='m_reverse_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->memberReverse(
                $data->faculty_id,
                $data->reason,
                $data->money_passwords,
                $data->amount
            )
        )
        );
    }
    if($request==='check_other_reverse_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->pastorOthersReverse(
                $data->faculty_id,
                $data->reason,
                $data->money_passwords,
                $data->amount
            )
        )
        );
    }
    if($request==='check_s_reverse_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->pastorSsReverse(
                $data->faculty_id,
                $data->reason,
                $data->money_passwords,
                $data->amount
            )
        )
        );
    }
    if($request==='check_d_reverse_transaction'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->pastorDsReverse(
                $data->faculty_id,
                $data->reason,
                $data->money_passwords,
                $data->amount
            )
        )
        );
    }
    if($request==='edit_bible_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editBibleAttendance(
                $data->id,
                $data->date,
                $data->attendance
            )
        )
        );
    }
    /* Ausman
   * Create a Department
   * 11/12/2020
   */
    if($request==='create_department'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createDepartment(
                $data->name
            )
        )
        );
    }
    /* Ausman
* create holiday
* 11/12/2020
*/
    if($request==='create_holiday'){
        include ("controllers/human_resource.php");
        $patient = new human_resource();
        exit(
        json_encode(
            $patient->createHoliday(
                $data->employee,
                $data->type,
                $data->start_date,
                $data->end_date
            )
        )
        );
    }
    /* Ausman
* Edit holiday
* 11/12/2020
*/
    if($request==='edit_holiday'){
        include ("controllers/human_resource.php");
        $patient = new human_resource();
        exit(
        json_encode(
            $patient->editHoliday(
                $data->id,
                $data->start_date,
                $data->end_date
            )
        )
        );
    }
    if($request==='create_employee'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createEmployee(
                $data->name,
                $data->dob,
                $data->village,
                $data->phone_number,
                $data->email,
                $data->id,
                $data->join,
                $data->department,
                $data->position,
                $data->status,
                $data->gender,
                $data->qualification,
                $data->relation,
                $data->next_gender,
                $data->next_email,
                $data->next_phone_number,
                $data->next_name
            )
        )
        );
    }
    if($request==='edit_employee'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editEmployee(
                $data->name_id,
                $data->name,
                $data->dob,
                $data->village,
                $data->phone_number,
                $data->email,
                $data->id,
                $data->join,
                $data->department,
                $data->position,
                $data->status,
                $data->gender,
                $data->qualification,
                $data->relation,
                $data->next_gender,
                $data->next_email,
                $data->next_phone_number,
                $data->next_name
            )
        )
        );
    }
    /* Ausman
* delete a department
* 11/12/2020
*/
    if($request==='delete_department'){
        include ("controllers/human_resource.php");
        $patient = new human_resource();
        exit(
        json_encode(
            $patient->deleteDepartment(
                $data->department_id
            )
        )
        );
    }
    if($request==='delete_home_cell'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->deleteHomecell(
                $data->faculty_id
            )
        )
        );
    }
    if($request==='create_homecells'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createHomecells(
                $data->name,
                $data->id
            )
        )
        );
    }
    if($request==='create_homecell'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createHomecell(
                $data->name
            )
        )
        );
    }
    if($request==='create_church_ministry'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createChurchMinistry(
                $data->church,
                $data->id
            )
        )
        );
    }
    if($request==='create_ministry'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createMinistry(
                $data->name
            )
        )
        );
    }
    if($request==='create_church_bible_class'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createChurchBibleClass(
                $data->church,
                $data->id
            )
        )
        );
    }
    if($request==='create_bible_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createChurchBibleClassAttendance(
                $data->array,
                $data->date
            )
        )
        );
    }
    if($request==='create_home_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createHomeAttendance(
                $data->array,
                $data->date
            )
        )
        );
    }
    if($request==='create_new_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createChurchAttendance(
                $data->attendance,
                $data->date_attendance,
                $data->id
            )
        )
        );
    }
    if($request==='create_monthly_attendance'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createChurchMonthlyAttendance(
                $data->children,
                $data->youth,
                $data->women,
                $data->men,
                $data->visitor,
                $data->new_people,
                $data->baptized,
                $data->id,
                $data->date,
                $data->air,
                $data->holly
            )
        )
        );
    }
    if($request==='create_class'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createClass(
                $data->name
            )
        )
        );
    }
    if($request==='edit_class'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editClass(
                $data->name,
                $data->id
            )
        )
        );
    }

    if($request==='edit_homecell'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editHomecell(
                $data->name,
                $data->id
            )
        )
        );
    }
    if($request==='edit_ministry'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->editMinistry(
                $data->name,
                $data->id
            )
        )
        );
    }
    if($request==='restore_church'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->restoreChurch(
                $data->church_id
            )
        )
        );
    }

    if($request==='create_section'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createSection(
                $data->name,
                $data->faculty
            )
        )
        );
    }

    if($request==='create_district'){
        include("controllers/human_resource.php");
        $faculty = new human_resource();
        exit(
        json_encode(
            $faculty->createDistrict(
                $data->name,
                $data->division
            )
        )
        );
    }
}
