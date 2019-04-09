import { Repo } from './repo'

export class RepoSearchResults {
  total_count: number
  incomplete_repos: boolean
  items: Repo[]
}
