import React, { Component } from 'react';
import { QuestionList } from '../components/QuestionList';
import { Redirect } from 'react-router';

interface QuestionPageState {
	redirectToResult: boolean;
	email: string;
}

export class QuestionPage extends Component<{}, QuestionPageState> {
	constructor(props: Readonly<{}>) {
		super(props);
		this.state = { email: '', redirectToResult: false };
	}

	onQuestionsSave(email: string) {
		this.setState({ email: email, redirectToResult: true });
	}
	render() {
		if (this.state.redirectToResult) {
			const url = `/results/${this.state.email}`;
			return <Redirect to={url} />;
		}

		return (
			<div>
				<h1>Discover Your Perspective</h1>
				<p>Complete the 7 min test and get detailed report of your lenses on the world.</p>
				<QuestionList onAnswersSaved={(email: string) => this.onQuestionsSave(email)} />
			</div>
		);
	}
}
