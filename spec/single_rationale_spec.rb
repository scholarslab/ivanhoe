require 'spec_helper'

describe 'Single Rationale View', :type => :feature, :js => true do

    include ApplicationHelper

    before(:each) do
        visit(URL_BASE)
        click_link('Games')
        login
        make_game
        first('a .game-title').click
        make_role
        make_a_move
        within('#game-data article.role') do
            first('a').click
        end
        within('.rationales') do
            first('a').click
        end
    end

    it 'has the rationale title' do
      expect(page).to have_content('JOURNAL ENTRY FOR')
      expect(page).to have_selector('article h1')
    end

    it 'has the rationale content' do
      expect(page).to have_selector('article p', :match => :first)
    end

end
