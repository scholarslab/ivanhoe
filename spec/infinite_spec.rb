require 'spec_helper'

describe "A single game view", :type => :feature, :js => true do

    include ApplicationHelper

    before(:each) do
      visit(URL_BASE)
      click_link 'Games'
      login
      rand(2..5).times { make_game }
    end

    describe 'A game with infinite scroll' do

        before do
          first('a .game-title').click
          game_page = current_url
          make_role
          visit game_page
          11.times { make_a_move }
          page.execute_script('window.scrollTo(0,100000)')
        end

        it 'loads the second page of moves' do
            expect(page).to have_selector('article.ivanhoe_move', count: 11)
        end

    end

end
