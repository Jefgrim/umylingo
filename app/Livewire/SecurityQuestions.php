<?php

namespace App\Livewire;

use App\Models\SecurityQuestion;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SecurityQuestions extends Component
{
    public $questions = [];
    public $answers = [];
    public $availableQuestions = [
        "What was the name of your first pet?",
        "What city were you born in?",
        "What is your mother's maiden name?",
        "What was the name of your first school?",
        "What is your favorite book?",
        "What was your childhood nickname?",
        "In what city did you meet your spouse/significant other?",
        "What is the name of your favorite childhood friend?",
    ];

    public function mount()
    {
        $existingQuestions = auth()->user()->securityQuestions;
        
        if ($existingQuestions->count() > 0) {
            $this->questions = $existingQuestions->pluck('question')->toArray();
        } else {
            // Initialize with 3 empty slots
            $this->questions = ['', '', ''];
            $this->answers = ['', '', ''];
        }
    }

    public function save()
    {
        // Remove existing questions
        auth()->user()->securityQuestions()->delete();

        $validCount = 0;
        foreach ($this->questions as $index => $question) {
            $answer = $this->answers[$index] ?? '';
            
            if (!empty($question) && !empty($answer)) {
                SecurityQuestion::create([
                    'user_id' => auth()->id(),
                    'question' => $question,
                    'answer' => Hash::make(strtolower(trim($answer))),
                ]);
                $validCount++;
            }
        }

        if ($validCount === 0) {
            session()->flash('error', 'Please add at least one security question.');
            return;
        }

        session()->flash('message', 'Security questions saved successfully.');
        $this->mount(); // Reload
    }

    public function render()
    {
        return view('livewire.security-questions');
    }
}

