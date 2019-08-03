import React from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';
import './App.css';
import { ResultPage } from './pages/ResultPage';
import { QuestionPage } from './pages/QuestionPage';

const App: React.FC = () => {
	return (
		<Router>
			<div className="container">
				<Route path="/" exact component={QuestionPage} />
				<Route path="/results/:email" exact component={ResultPage} />
			</div>
		</Router>
	);
};

export default App;
