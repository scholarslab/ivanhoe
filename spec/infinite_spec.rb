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
          first('.game-title a').click
          make_role
          11.times { make_a_move }
          page.execute_script('window.scrollTo(0,100000)')
        end

        it 'loads the second page of moves' do
            expect(page).to have_selector('article.ivanhoe_move', count: 11)
        end

        # go to the game
        # make a role
        # make at least 10 moves
        # scroll to the bottom of the page to trigger infinite scroll
        # create an array of moves
        # check for something in the 11th move

    end

end
