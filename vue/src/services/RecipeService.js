import { makeApiRequest } from '@/plugins/api'

class RecipeService {
  getRecipe(slug) {
    const config = {
      method: 'get',
      url: `recipe/${slug}`,
    }
    return makeApiRequest(config)
  }
  getRecipes(page) {
    const config = {
      method: 'get',
      url: 'recipes',
      params: {
        page: page ?? 1,
      },
    }
    return makeApiRequest(config)
  }
}

export default new RecipeService()
