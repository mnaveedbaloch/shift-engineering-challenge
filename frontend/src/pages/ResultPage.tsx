import React, { Component } from 'react';
import { PerspectiveDimention } from '../components/PerspectiveDimention';
import { RouteComponentProps } from 'react-router';
import { AjaxService } from '../helpers/AjaxService';
import { GetPerspectiveResponse } from '../models/GetPerspectiveResponse';

interface TParams {
	email: string;
}

interface ResultPageState {
	dimentions: { dimention: string; direction: string }[];
	perspective: string;
}

export class ResultPage extends Component<RouteComponentProps<TParams>, ResultPageState> {
	constructor(props: RouteComponentProps<TParams>) {
		super(props);
		this.state = { perspective: '', dimentions: [] };
	}
	componentDidMount() {
		this.loadPerspective(this.props.match.params.email);
	}

	private async loadPerspective(email: string) {
		try {
			const response = await new AjaxService().get<GetPerspectiveResponse>('user/perspective', { email: email });
			if (response.perspective) {
				this.setState({ perspective: response.perspective, dimentions: response.dimentions });
			} else {
				alert(response.errors.join('<br>'));
			}
		} catch {
			console.error('Server error');
		}
	}

	render() {
		const perceptions: any = [];
		this.state.dimentions.forEach((d) => {
			perceptions.push(<PerspectiveDimention key={d.dimention} dimention={d.dimention} perception={d.direction} />);
		});
		return (
			<div className="result-page card">
				<div className="card-body">
					<div className="row">
						<div className="col-lg-4 col-sm-12">
							<h3>Your Perspective</h3>
							<p>
								Your Perspective type is <strong>{this.state.perspective}</strong>
							</p>
						</div>
						<div className="col-lg-8 col-sm-12">{perceptions}</div>
					</div>
				</div>
			</div>
		);
	}
}
