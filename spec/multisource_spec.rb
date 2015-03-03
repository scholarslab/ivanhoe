require 'spec_helper'

describe 'Responding to multiple moves', :type => :feature, :js => true do

    include ApplicationHelper

    @valid_game = {
        :game_title => Faker::Lorem.words(rand(2..8)),
        :game_description => Faker::Lorem.paragraphs(rand(1..3))
    }

before(:each) do
    visit(URL_BASE)
     click_link 'Games'
     login
     rand(2..3).times { make_game }
end

describe "a view with a response with multiple sources"

    before do
      first('.game-link').click
      make_role
      2.times { make_a_move }
      @moves = page.all('#moves article')
      first_move_button = @moves[0].find('.new_source', match: :first)
      second_move_button = @moves[1].find('.new_source', match: :first)
      first_move_button.click
      second_move_button.click
      click_button 'Respond'
      fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
      tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
      tiny_mce_fill_in_post_rationale('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
      click_button 'Save'
    end

    it 'should fail' do
      source_block = first('#moves article .game-discussion-source')
      within(source_block) do
        expect(find('ul')).to have_selector('li', count: 2)
      end
    end

    # need to abstract this to make an actual respond to multiple move helper function
    # Would create 10 moves...click random number of moves from moves array

end
