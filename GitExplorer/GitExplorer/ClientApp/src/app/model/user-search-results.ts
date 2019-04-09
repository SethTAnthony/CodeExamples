import { User } from './user'

export class UserSearchResults {
  total_count: number
  incomplete_results: boolean
  items: User[]
}
