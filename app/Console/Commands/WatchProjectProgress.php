<?php

namespace App\Console\Commands;

use App\Models\Answer;
use App\Models\EmailList;
use App\Models\Project;
use App\Models\ProjectCertificate;
use App\Models\Question;
use App\Models\User;
use App\Models\VendorDueDiligence\User as VendorUser;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class WatchProjectProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:watch-project-progress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command watch the progress of client progress and updates it accordingly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // public function watchProgress()
    // {
    //     $projects = Project::where(['year' => date('Y', strtotime('now'))])->get();

    //     foreach ($projects as $project) {
    //         $project->watchProjectProgress($project);

    //         //create certificate
    //         $certificate_object = new ProjectCertificate();
    //         $certificate_object->create($project);
    //     }
    // }

    public function deleteAnswersForDeletedQuestions()
    {
        Question::onlyTrashed()->chunk(100, function (Collection $questions) {
            foreach ($questions as $question) {
                Answer::where('question_id', $question->id)->delete();
            }
        });
    }

    public function populateEmailList()
    {
        User::chunkById(100, function (Collection $users) {
            foreach ($users as $user) {
                EmailList::firstOrCreate(['email' => $user->email, 'name' => $user->name]);
            }
        }, $column = 'id');

        VendorUser::chunkById(100, function (Collection $users) {
            foreach ($users as $user) {
                EmailList::firstOrCreate(['email' => $user->email, 'name' => $user->name]);
            }
        }, $column = 'id');
        return;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->populateEmailList();
        // $this->deleteAnswersForDeletedQuestions();
    }
}
