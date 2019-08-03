import React, { Component } from 'react';
import QuestionListItem from './QuestionListItem';
import { AjaxService } from '../helpers/AjaxService';
import { Question } from '../models/Question';
import { QuestionListState } from '../models/QuestionListState';
import { GetQuestionResponse } from '../models/GetQuestionResponse';

interface QuestionListProps {
	onAnswersSaved: (email: string) => void;
}

export class QuestionList extends Component<QuestionListProps, QuestionListState> {
	/**
	 * Hash structure for keeping question id's and choosen number
	 * Where hash key in the question id and the values
	 * is choosen number
	 */
	private answeredQuestions: any = {};

	constructor(props: Readonly<QuestionListProps>) {
		super(props);
		this.state = { questions: [], email: '', saveInProgress: false };
	}

	componentDidMount() {
		this.fetchQuestions();
	}

	/**
	 * Fetch questions list from database and update them in state
	 */
	private async fetchQuestions() {
		try {
			const resposne = await new AjaxService().get<GetQuestionResponse>('questions');
			this.setState({ questions: resposne.questions });
		} catch {
			console.error('Error loading questions');
		}
	}

	/**
	 *
	 * @param questionId
	 * @param selectedAnswer
	 */
	onQuestionAnswered(questionId: number, selectedAnswer: number): void {
		this.answeredQuestions[questionId] = selectedAnswer;
	}

	onSaveAnswersBtnClkd() {
		if (Object.keys(this.answeredQuestions).length < this.state.questions.length) {
			alert('Please answer all question before save');
			return;
		}
		this.saveAnsweresInDatabase();
	}

	async saveAnsweresInDatabase() {
		const answeredQuestionsList: any[] = [];
		Object.keys(this.answeredQuestions).forEach((questionId) => {
			answeredQuestionsList.push({ q_id: questionId, choosen_number: this.answeredQuestions[questionId] });
		});

		this.setState({ saveInProgress: true });

		try {
			const requestData = {
				answeredQuestions: answeredQuestionsList,
				email: this.state.email
			};
			const response = await new AjaxService().post('user-answers/store-bulk', requestData);

			if (!response.success) {
				alert(response.errors.join('<br>'));
				return;
			}

			this.setState({ saveInProgress: false });
			this.props.onAnswersSaved(this.state.email);
		} catch {
			console.error('Server error');
			this.setState({ saveInProgress: false });
		}
	}

	handleEmailChange(event: { target: { value: string } }) {
		this.setState({ email: event.target.value });
	}

	render() {
		const questions: any[] = [];
		this.state.questions.forEach((q: Question) => {
			questions.push(
				<QuestionListItem
					key={q.id}
					description={q.description}
					onSelect={(selectedAnswer: number) => this.onQuestionAnswered(q.id, selectedAnswer)}
				/>
			);
		});

		return (
			<div>
				<div className="row">
					<div className="offset-md-2 col-md-8">
						<div className="question-list-container">{questions}</div>
						<div className="card email">
							<div className="card-body">
								<h3>Your Email</h3>
								<input
									value={this.state.email}
									onChange={(event) => this.handleEmailChange(event)}
									type="email"
									className="form-control mt-4"
									placeholder="you@example.com"
								/>
							</div>
						</div>
					</div>
				</div>
				<div className="row mt-5 mb-5 text-align-center">
					<div className="offset-md-2 col-md-8">
						<button
							disabled={this.state.saveInProgress}
							className="btn btn-primary btn-lg"
							onClick={() => this.onSaveAnswersBtnClkd()}
						>
							Save & Continue
						</button>
					</div>
				</div>
			</div>
		);
	}
}
