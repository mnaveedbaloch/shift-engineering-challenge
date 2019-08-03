import { Question } from './Question';

export interface QuestionListState {
	questions: Question[];
	email: string;
	saveInProgress: boolean;
}
