<?php

namespace App\Services;

use App\Data\Quiz\QuizAnswer;
use App\Data\Quiz\QuizQuestion;
use Illuminate\Support\Collection;

class QuizService
{
    public function getQuestions(): Collection
    {
        return collect([
            new QuizQuestion(
                id: 1,
                text: __('quiz.questions.1.text'),
                hint: __('quiz.questions.1.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.1.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.1.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.1.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.1.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 2,
                text: __('quiz.questions.2.text'),
                hint: __('quiz.questions.2.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.2.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.2.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.2.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.2.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 3,
                text: __('quiz.questions.3.text'),
                hint: __('quiz.questions.3.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.3.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.3.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.3.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.3.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 4,
                text: __('quiz.questions.4.text'),
                hint: __('quiz.questions.4.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.4.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.4.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.4.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.4.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 5,
                text: __('quiz.questions.5.text'),
                hint: __('quiz.questions.5.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.5.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.5.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.5.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.5.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 6,
                text: __('quiz.questions.6.text'),
                hint: __('quiz.questions.6.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.6.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.6.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.6.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.6.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 7,
                text: __('quiz.questions.7.text'),
                hint: __('quiz.questions.7.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.7.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.7.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.7.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.7.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 8,
                text: __('quiz.questions.8.text'),
                hint: __('quiz.questions.8.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.8.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.8.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.8.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.8.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 9,
                text: __('quiz.questions.9.text'),
                hint: __('quiz.questions.9.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.9.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.9.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.9.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.9.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 10,
                text: __('quiz.questions.10.text'),
                hint: __('quiz.questions.10.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.10.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.10.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.10.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.10.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 11,
                text: __('quiz.questions.11.text'),
                hint: __('quiz.questions.11.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.11.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.11.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.11.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.11.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 12,
                text: __('quiz.questions.12.text'),
                hint: __('quiz.questions.12.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.12.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.12.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.12.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.12.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 13,
                text: __('quiz.questions.13.text'),
                hint: __('quiz.questions.13.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.13.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.13.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.13.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.13.answers.4'), correct: true),
                ]
            ),
            new QuizQuestion(
                id: 14,
                text: __('quiz.questions.14.text'),
                hint: __('quiz.questions.14.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.14.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.14.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.14.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.14.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 15,
                text: __('quiz.questions.15.text'),
                hint: __('quiz.questions.15.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.15.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.15.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.15.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.15.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 16,
                text: __('quiz.questions.16.text'),
                hint: __('quiz.questions.16.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.16.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.16.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.16.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.16.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 17,
                text: __('quiz.questions.17.text'),
                hint: __('quiz.questions.17.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.17.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.17.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.17.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.17.answers.4'), correct: true),
                ]
            ),
            new QuizQuestion(
                id: 18,
                text: __('quiz.questions.18.text'),
                hint: __('quiz.questions.18.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.18.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.18.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.18.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.18.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 19,
                text: __('quiz.questions.19.text'),
                hint: __('quiz.questions.19.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.19.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.19.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.19.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.19.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 20,
                text: __('quiz.questions.20.text'),
                hint: __('quiz.questions.20.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.20.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.20.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.20.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.20.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 21,
                text: __('quiz.questions.21.text'),
                hint: __('quiz.questions.21.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.21.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.21.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.21.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.21.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 22,
                text: __('quiz.questions.22.text'),
                hint: __('quiz.questions.22.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.22.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.22.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.22.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.22.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 23,
                text: __('quiz.questions.23.text'),
                hint: __('quiz.questions.23.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.23.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.23.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.23.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.23.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 24,
                text: __('quiz.questions.24.text'),
                hint: __('quiz.questions.24.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.24.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.24.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.24.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.24.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 25,
                text: __('quiz.questions.25.text'),
                hint: __('quiz.questions.25.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.25.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.25.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.25.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.25.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 26,
                text: __('quiz.questions.26.text'),
                hint: __('quiz.questions.26.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.26.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.26.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.26.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.26.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 27,
                text: __('quiz.questions.27.text'),
                hint: __('quiz.questions.27.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.27.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.27.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.27.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.27.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 28,
                text: __('quiz.questions.28.text'),
                hint: __('quiz.questions.28.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.28.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.28.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.28.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.28.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 29,
                text: __('quiz.questions.29.text'),
                hint: __('quiz.questions.29.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.29.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.29.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.29.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.29.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 30,
                text: __('quiz.questions.30.text'),
                hint: __('quiz.questions.30.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.30.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.30.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.30.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.30.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 31,
                text: __('quiz.questions.31.text'),
                hint: __('quiz.questions.31.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.31.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.31.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.31.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.31.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 32,
                text: __('quiz.questions.32.text'),
                hint: __('quiz.questions.32.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.32.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.32.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.32.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.32.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 33,
                text: __('quiz.questions.33.text'),
                hint: __('quiz.questions.33.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.33.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.33.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.33.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.33.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 34,
                text: __('quiz.questions.34.text'),
                hint: __('quiz.questions.34.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.34.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.34.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.34.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.34.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 35,
                text: __('quiz.questions.35.text'),
                hint: __('quiz.questions.35.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.35.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.35.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.35.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.35.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 36,
                text: __('quiz.questions.36.text'),
                hint: __('quiz.questions.36.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.36.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.36.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.36.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.36.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 37,
                text: __('quiz.questions.37.text'),
                hint: __('quiz.questions.37.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.37.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.37.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.37.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.37.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 38,
                text: __('quiz.questions.38.text'),
                hint: __('quiz.questions.38.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.38.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.38.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.38.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.38.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 39,
                text: __('quiz.questions.39.text'),
                hint: __('quiz.questions.39.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.39.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.39.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.39.answers.3'), correct: true),
                    new QuizAnswer(id: 4, text: __('quiz.questions.39.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 40,
                text: __('quiz.questions.40.text'),
                hint: __('quiz.questions.40.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.40.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.40.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.40.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.40.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 41,
                text: __('quiz.questions.41.text'),
                hint: __('quiz.questions.41.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.41.answers.1'), correct: true),
                    new QuizAnswer(id: 2, text: __('quiz.questions.41.answers.2')),
                    new QuizAnswer(id: 3, text: __('quiz.questions.41.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.41.answers.4')),
                ]
            ),
            new QuizQuestion(
                id: 42,
                text: __('quiz.questions.42.text'),
                hint: __('quiz.questions.42.hint'),
                answers: [
                    new QuizAnswer(id: 1, text: __('quiz.questions.42.answers.1')),
                    new QuizAnswer(id: 2, text: __('quiz.questions.42.answers.2'), correct: true),
                    new QuizAnswer(id: 3, text: __('quiz.questions.42.answers.3')),
                    new QuizAnswer(id: 4, text: __('quiz.questions.42.answers.4')),
                ]
            ),
        ]);
    }
}
