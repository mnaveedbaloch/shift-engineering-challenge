import React from 'react';

interface QuestionListItemProps {
	description: string;
	onSelect: (selectedAnswer: number) => void;
}

export class QuestionListItem extends React.Component<QuestionListItemProps> {
	render() {
		const radioOptions = [];
		const randomNameForGroupOfRadios = (Math.random() * 10000).toString();
		for (let i = 1; i <= 7; i++) {
			radioOptions.push(
				<label className="radio-container" key={i}>
					<input name={randomNameForGroupOfRadios} type="radio" onClick={() => this.props.onSelect(i)} />
					<span className="checkmark" />
				</label>
			);
		}
		return (
			<div className="card">
				<div className="card-body">
					<strong> {this.props.description} </strong>
					<div className="row mt-3">
						<div className="col-sm-2  ">
							<span className="pull-right d-none d-md-block">Disagree</span>
							<span className='d-sm-block d-md-none'>Disagree - Agree</span>
						</div>
						<div className="col-sm-8">{radioOptions}</div>
						<div className="col-sm-2 d-none d-md-block">Agree</div>
					</div>
				</div>
			</div>
		);
	}
}

export default QuestionListItem;
