import React, { Component } from 'react';

interface PerspectiveDimentionProps {
	perception: string;
	dimention: string;
}
/**
 * To render a single dimention bar, highlights the preception user leans to
 */
export class PerspectiveDimention extends Component<PerspectiveDimentionProps, {}> {
	private getPerceptionFullName($perceptionChar: string): string {
		let perceptionFullName = '';
		switch ($perceptionChar) {
			case 'I':
				perceptionFullName = 'Introversion';
				break;
			case 'E':
				perceptionFullName = 'Extraversion';
				break;
			case 'N':
				perceptionFullName = 'Intuition';
				break;
			case 'T':
				perceptionFullName = 'Thinking';
				break;
			case 'P':
				perceptionFullName = 'Perceiving';
				break;
			case 'F':
				perceptionFullName = 'Feeling';
				break;
			case 'J':
				perceptionFullName = 'Judging';
				break;
			case 'S':
				perceptionFullName = 'Sensing';
				break;
			default:
				console.error('encounted undefined perception character');
				break;
		}
		return perceptionFullName;
	}

	render() {
		const dimentionComponents = this.props.dimention.split('');
		const firstComponent: any = dimentionComponents[0];
		const secondComponent = dimentionComponents[1];

		return (
			<div className="perspective-dimention-item">
				<div className="row mb-3">
					<div className="col-sm-3">
						<span className="d-none d-md-block">
							{this.getPerceptionFullName(firstComponent)} ({firstComponent})
						</span>
					</div>
					<div className="col-sm-6">
						<div className="bar">
							<div
								className={'item left-border-radius ' + (firstComponent === this.props.perception ? 'filled' : '')}
							/>
							<div
								className={'item right-border-radius ' + (secondComponent === this.props.perception ? 'filled' : '')}
							/>
						</div>
					</div>
					<div className="col col-sm-3">
						<span className="d-none d-md-block">
							{this.getPerceptionFullName(secondComponent)} ({secondComponent})
						</span>
					</div>
				</div>
			</div>
		);
	}
}
